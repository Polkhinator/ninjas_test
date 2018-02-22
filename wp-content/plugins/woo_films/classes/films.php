<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Films' ) ) :

  /**
   * Films post type functionality.
   */
  class Films {

    protected static $_instance = NULL;

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
     * Films constructor.
     */
    public function __construct() {
      $this->init_hooks();
    }

    /**
     * Init hooks filters and shortcodes.
     */
    protected function init_hooks() {
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
      // Actions section.
      add_action( 'init', [ $this, 'register_films_post_type' ] );
      add_action( 'init', [ $this, 'register_films_category_taxonomy' ] );
      add_action( 'add_meta_boxes', [ $this, 'films_meta_boxes' ] );
      add_action( 'save_post', [ $this, 'save_film_post' ] );
      // Filters section.
      add_filter( 'woocommerce_product_get_price', [
        $this,
        'films_woocommerce_get_price',
      ], 10, 2 );
      add_filter( 'the_content', [ $this, 'films_add_to_cart_button' ], 20, 1 );
      add_filter( 'the_excerpt', [ $this, 'films_add_to_cart_button' ], 20, 1 );
      add_filter( 'single_template', [ $this, 'single_film_template' ] );
      // Shortcodes section.
      add_shortcode( 'films_list', [ $this, 'films_list' ] );
    }

    /**
     * Post type registering.
     */
    public function register_films_post_type() {
      register_post_type( 'films', $this->get_films_post_type_arguments() );
    }

    /**
     * Taxonomy registering.
     */
    public function register_films_category_taxonomy() {
      register_taxonomy( 'films_category', [ 'films' ], $this->get_films_category_arguments() );
    }

    /**
     * Separate function for post type arguments declaration.
     */
    protected function get_films_post_type_arguments() {
      $labels = [
        'name'               => _x( 'Films', 'post type general name' ),
        'singular_name'      => _x( 'Film', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Film' ),
        'add_new_item'       => __( 'Add New Film' ),
        'edit_item'          => __( 'Edit Film' ),
        'new_item'           => __( 'New Film' ),
        'all_items'          => __( 'All Films' ),
        'view_item'          => __( 'View Film' ),
        'search_items'       => __( 'Search films' ),
        'not_found'          => __( 'No films found' ),
        'not_found_in_trash' => __( 'No films found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Films',
      ];

      $args = [
        'labels'        => $labels,
        'description'   => 'Woo Films',
        'public'        => TRUE,
        'menu_position' => 5,
        'supports'      => [
          'title',
          'editor',
          'thumbnail',
          'excerpt',
          'comments',
        ],
        'has_archive'   => TRUE,
      ];

      return $args;
    }

    /**
     * Separate function for taxonomy arguments declaration.
     */
    protected function get_films_category_arguments() {
      $labels = [
        'name'              => _x( 'Films categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Films category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Films categories' ),
        'all_items'         => __( 'All Films categories' ),
        'parent_item'       => __( 'Parent Films category' ),
        'parent_item_colon' => __( 'Parent Films category:' ),
        'edit_item'         => __( 'Edit Films category' ),
        'update_item'       => __( 'Update Films category' ),
        'add_new_item'      => __( 'Add New Films category' ),
        'new_item_name'     => __( 'New Films category Name' ),
        'menu_name'         => __( 'Films categories' ),
      ];


      $args = [
        'hierarchical'      => TRUE,
        'labels'            => $labels,
        'show_ui'           => TRUE,
        'show_admin_column' => TRUE,
        'query_var'         => TRUE,
        'rewrite'           => [ 'slug' => 'films_category' ],
      ];

      return $args;
    }

    /**
     * Registering meta boxes.
     */
    public function films_meta_boxes() {
      add_meta_box( 'films_fields', __( 'Film details' ), [
        $this,
        'films_fields_callback',
      ], 'films' );
    }

    /**
     * Meta boxes HTML.
     */
    public function films_fields_callback( $post ) {
      wp_nonce_field( 'films_box_nonce', 'films_box_nonce' );

      $subtitle  = get_post_meta( $post->ID, 'subtitle', TRUE );
      $featured  = get_post_meta( $post->ID, 'featured', TRUE );
      $woo_price = get_post_meta( $post->ID, 'woo_price', TRUE );

      ?>
        <div class="films-fields-wrapper">

            <p>
                <label for="subtitle"><?php _e( 'Subtitle' ); ?></label>
            </p>
            <input type="text" id="subtitle" name="subtitle" size="65"
                   value="<?php echo ! empty( $subtitle ) ? $subtitle : ''; ?>"/>

            <p>
                <input type="checkbox" name="featured" id="featured"
                       value="1" <?php checked( $featured, 1 ); ?>>
                <label for="featured"><?php _e( 'Featured film' ); ?></label>
            </p>

            <p>
                <label for="woo_price"><?php _e( 'Price' ); ?></label>
            </p>
            <input type="number" name="woo_price"
                   value="<?php echo ! empty( $woo_price ) ? $woo_price : ''; ?>">

        </div>
      <?php
    }

    /**
     * Save entered data to the metaboxes.
     */
    public function save_film_post( $post_id ) {
      if ( ! isset( $_POST['films_box_nonce'] ) ) {
        return $post_id;
      }

      $nonce = $_POST['films_box_nonce'];
      if ( ! wp_verify_nonce( $nonce, 'films_box_nonce' ) ) {
        return $post_id;
      }

      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
      }

      $featured  = $_POST['featured'];
      $subtitle  = $_POST['subtitle'];
      $woo_price = $_POST['woo_price'];

      if ( empty( $featured ) ) {
        update_post_meta( $post_id, 'featured', 0 );
      } else {
        update_post_meta( $post_id, 'featured', $featured );
      }

      update_post_meta( $post_id, 'subtitle', $subtitle );
      update_post_meta( $post_id, 'woo_price', $woo_price );

    }

    /**
     * Make film buyable.
     */
    public function films_woocommerce_get_price( $price, $post ) {
      if ( $post->post->post_type === 'films' ) {
        $price = get_post_meta( $post->id, "woo_price", TRUE );
      }

      return $price;
    }

    /**
     * Integration "Buy now" button to the films content and excerpts.
     */
    public function films_add_to_cart_button( $content ) {
      global $post;
      if ( $post->post_type !== 'films' ) {
        return $content;
      }

      if (strpos($content, '<article') === 0) {
        return $content;
      }

      $price = get_post_meta( $post->ID, 'woo_price', TRUE );

      ob_start();
      ?>
        <form action="" method="post">
            <input type="hidden" value="<?php echo $price ?>" name="woo_price">
            <input name="add-to-cart" type="hidden"
                   value="<?php echo $post->ID ?>"/>
            <input name="submit" type="submit" value="Buy now"/>
        </form>
      <?php

      return $content . ob_get_clean();
    }

    /**
     * [films_list] shortcode callback.
     * Shortcode parameters:
     * num - amount of films
     * featured = 1 - to show only featured films.
     *
     * @param $atts
     *
     * @return string
     */
    public function films_list( $atts ) {
      $atts['num'] = ! empty( $atts['num'] ) ? $atts['num'] : 10;
      $args        = [
        'post_type'      => 'films',
        'post_status'    => 'publish',
        'posts_per_page' => $atts['num'],
        'orderby'        => 'post_date',
        'order'          => 'DESC',
      ];
      if ( ! empty( $atts['featured'] ) ) {
        $args['meta_key']   = 'featured';
        $args['meta_value'] = 1;
      }
      $films_query = new WP_Query( $args );
      ob_start();
      if ( $films_query->have_posts() ):
        do_action( 'films_before_loop' );
        while ( $films_query->have_posts() ): $films_query->the_post();
          films_get_template_part( 'films', 'list' );
        endwhile;
        do_action( 'films_after_loop' );
      endif;
      $output = ob_get_clean();

      return $output;
    }

    /**
     * Template overriding for the films post type.
     */
    public function single_film_template( $single_template ) {
      global $post;
      if ( $post->post_type == 'films' ) {
        $single_template = films_get_template_part( 'single', 'films', FALSE );
      }

      return $single_template;
    }

  }


endif;

Films::getInstance();