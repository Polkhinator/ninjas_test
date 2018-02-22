<?php
/**
 * Plugin Name: Woocommerce Films Integration
 * Plugin URI: http://woo-films.me
 * Description: Films products for Woocommerce.
 * Version: 0.1
 * Author: Dmitry Polkhov
 * Author URI: http://authors.page
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// Register function on activate plugin action.
register_activation_hook( __FILE__, [ 'Woo_Films', 'install' ] );

if ( ! class_exists( 'Woo_Films' ) ) :

  /**
   * Main initial class.
   */
  class Woo_Films {

    protected static $_instance = NULL;

    protected $options;

    /**
     * Singleton implementation.
     */
    public static function getInstance() {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
      }

      return self::$_instance;
    }

    /**
     * Woo_Films constructor.
     */
    public function __construct() {
      $this->add_includes();
      $this->init_hooks();
    }

    /**
     * Load required includes.
     */
    protected function add_includes() {
      include_once dirname( __FILE__ ) . '/classes/films.php';
      include_once dirname( __FILE__ ) . '/classes/class-woo-films-settings-page.php';
      include_once dirname( __FILE__ ) . '/functions.php';
    }

    /**
     * Init hooks filters and shortcodes.
     */
    protected function init_hooks() {
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
      // Actions section.
      add_action( 'register_form', [ $this, 'user_add_skype_field' ] );
      add_action( 'user_register', [ $this, 'user_register' ] );
      add_action( 'show_user_profile', [
        $this,
        'user_profile_add_skype_field',
      ] );
      add_action( 'edit_user_profile', [
        $this,
        'user_profile_add_skype_field',
      ] );

      // Filters section.
      add_filter( 'login_redirect', [ $this, 'redirect_on_login' ], 10, 3 );
      add_filter( 'woocommerce_add_to_cart_redirect', [
        $this,
        'woo_skip_cart_page',
      ] );

      add_filter( 'woocommerce_data_stores', [
        $this,
        'films_woocommerce_data_stores',
      ] );

      // Init settings if current user is admin.
      if ( is_admin() ) {
        new Woo_Films_Settings_Page();
      }
    }

    /**
     * We need to create featured films list on first plugin activation
     * and set user redirect there.
     */
    public static function install() {
      $page_content = '[films_list featured=1]';
      $page_data    = [
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_author'  => 1,
        'post_content' => $page_content,
        'post_title'   => __( 'Featured Films List' ),
        'post_name'    => 'featured-films-list',
      ];
      $page_id      = wp_insert_post( $page_data );
      update_option( 'films_redirect', [ 'user_login_redirect_callback' => $page_id ] );
    }

    /**
     * Add skype field to user registration form.
     */
    public function user_add_skype_field() {
      $skype = ( ! empty( $_POST['skype'] ) ) ? trim( $_POST['skype'] ) : '';
      ?>
        <p>
            <label for="skype"><?php _e( 'Skype' ) ?><br/>
                <input type="text" name="skype" id="skype" class="input"
                       value="<?php echo esc_attr( $skype ); ?>"/>
            </label>
        </p>
      <?php
    }

    /**
     * Saving skype on account creation.
     */
    public function user_register( $user_id ) {
      if ( ! empty( $_POST['skype'] ) ) {
        update_user_meta( $user_id, 'skype', trim( $_POST['skype'] ) );
      }
    }

    /**
     * Add skype field to the user profile.
     */
    public function user_profile_add_skype_field( $user ) {
      ?>
        <h3><?php _e( 'Additional info' ); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="skype"><?php _e( 'Skype' ); ?></label></th>

                <td>
                    <input type="text" name="skype" id="skype"
                           value="<?php echo esc_attr( get_the_author_meta( 'skype', $user->ID ) ); ?>"
                           class="text"/><br/>
                    <span class="description"><?php _e( 'Please enter your skype' ); ?></span>
                </td>
            </tr>

        </table>
      <?php
    }

    /**
     * After login redirect callback.
     */
    public function redirect_on_login( $redirect_to, $request, $user ) {
      if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'administrator', $user->roles ) ) {
          return $redirect_to;
        } else {
          $redirect_settings = get_option( 'films_redirect' );
          if ( ! empty( $redirect_settings ) ) {
            return get_page_link( $redirect_settings['user_login_redirect_callback'] );
          } else {
            return $redirect_to;
          }
        }
      } else {
        return $redirect_to;
      }
    }

    /**
     * We're skipping cart here and immediately go to billing/checkout.
     */
    public function woo_skip_cart_page() {
      $url = get_permalink( get_option( 'woocommerce_checkout_page_id' ) );

      return $url;
    }

    /**
     * Avoid WooCommerce checking current post type is product to be able to
     * sell our films.
     */
    public function films_woocommerce_data_stores( $stores ) {
      require_once dirname( __FILE__ ) . '/classes/class-films-data-store-cpt.php';
      $stores['product'] = 'Films_Product_Data_Store_CPT';

      return $stores;
    }

  }

endif;

Woo_Films::getInstance();