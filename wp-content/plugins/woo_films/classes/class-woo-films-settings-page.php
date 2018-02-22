<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Woo_Films_Settings_Page' ) ) :

  /**
   * Class Woo_Films_Settings_Page.
   */
  class Woo_Films_Settings_Page {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
      add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );
      add_action( 'admin_init', [ $this, 'page_init' ] );
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
      // This page will be under "Settings"
      add_options_page(
        'User Redirect Settings',
        'User Redirect Settings',
        'manage_options',
        'user-redirect-settings',
        [ $this, 'create_user_redirect_settings_page' ]
      );
    }

    /**
     * Options page callback
     */
    public function create_user_redirect_settings_page() {
      // Set class property
      $this->options = get_option( 'films_redirect' );
      ?>
        <div class="wrap">
            <h1><?php _e('Redirect settings'); ?></h1>
            <form method="post" action="options.php">
              <?php
              // This prints out all hidden setting fields
              settings_fields( 'films_redirect' );
              do_settings_sections( 'user-redirect-settings' );
              submit_button();
              ?>
            </form>
        </div>
      <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
      register_setting(
        'films_redirect', // Option group
        'films_redirect' // Option name
      );

      add_settings_section(
        'setting_section_id', // ID
        '', // Title
        '', // Callback
        'user-redirect-settings' // Page
      );

      add_settings_field(
        'user_login_redirect_page', // ID
        'User Redirect Page', // Title
        [ $this, 'user_redirect_callback' ], // Callback
        'user-redirect-settings', // Page
        'setting_section_id' // Section
      );

    }

    /**
     * Select list with pages.
     */
    public function user_redirect_callback() {
      $pages = get_pages();
      ?>
        <select name="films_redirect[user_redirect_callback]">
            <option value=""><?php _e( 'Select page' ); ?></option>
          <?php foreach ( $pages as $page ) : ?>
              <option value="<?php echo $page->ID; ?>" <?php selected( $this->options['user_login_redirect_callback'], $page->ID ); ?>><?php echo $page->post_title; ?></option>
          <?php endforeach; ?>
        </select>
      <?php
    }

  }

endif;