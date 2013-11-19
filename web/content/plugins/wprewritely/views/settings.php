<?php

/**
* settings
*/
class wprewritely_settings extends WPOOP
{
  /**
   * Holds the class instance
   */
  public static $instance;

  function __construct()
  {
    self::$instance = $this;
    $this->hook( 'admin_init', 'onAdminInit' );
  }


  public function onAdminInit() {

    add_settings_section(
      'wprewritely_settings',
      'WP Rewritely Settings',
      array( &$this, 'wprewritely_settings_screen' ),
      'writing'
    );

    add_settings_field( 'wprewritely_posts',
      'Enable plugin for posts',
      array( &$this, 'checkbox_callback' ),
      'writing',
      'wprewritely_settings',
      'wprewritely_posts'
    );
    register_setting( 'writing', 'wprewritely_posts' );

    add_settings_field( 'wprewritely_pages',
      'Enable plugin for pages',
      array( &$this, 'checkbox_callback' ),
      'writing',
      'wprewritely_settings',
      'wprewritely_pages'
    );
    register_setting( 'writing', 'wprewritely_pages' );

    add_settings_field( 'wprewritely_email',
      'Activation e-mail',
      array( &$this, 'email_callback' ),
      'writing',
      'wprewritely_settings',
      'wprewritely_email'
    );
    register_setting( 'writing', 'wprewritely_email' );

  }

  public function checkbox_callback($name){
    ?>
      <input name="<?=$name?>" type="checkbox" id="<?=$name?>" value="1" <?=checked('1', get_option($name))?>  />
    <?php
  }

  public function email_callback($name){
    ?>
      <input name="<?=$name?>" type="email" id="<?=$name?>" value="<?=get_option($name)?>" />
    <?php
  }

  function wprewritely_settings_screen() {
    ?>
      <p>Here you can customize, where you want display WP Rewritely plugin.</p>
    <?php
  }

}

new wprewritely_settings;