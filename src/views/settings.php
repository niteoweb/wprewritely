<?php

/**
 * settings
 */
class wprewritely_settings
{

    function __construct()
    {
        add_action('admin_init', array(&$this, 'onAdminInit'));
    }


    public function onAdminInit()
    {

        add_settings_section(
            'wprewritely_settings',
            'WP Rewritely Settings',
            null,
            'writing'
        );

        add_settings_field('wprewritely_posts',
            'Enable plugin for posts',
            array(&$this, 'checkbox_callback'),
            'writing',
            'wprewritely_settings',
            'wprewritely_posts'
        );
        register_setting('writing', 'wprewritely_posts');

        add_settings_field('wprewritely_pages',
            'Enable plugin for pages',
            array(&$this, 'checkbox_callback'),
            'writing',
            'wprewritely_settings',
            'wprewritely_pages'
        );
        register_setting('writing', 'wprewritely_pages');

    }

    public function checkbox_callback($name)
    {
        ?>
        <input name="<?php echo $name; ?>" type="checkbox" id="<?php echo $name; ?>"
               value="1" <?php echo checked('1', get_option($name)); ?> />
        <?php
    }

}
