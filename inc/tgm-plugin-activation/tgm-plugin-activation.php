<?php

/**
 * TGM Plugin Activation
 * 
 * http://tgmpluginactivation.com/
 * 
 * Emample:
 * 
 * https://github.com/TGMPA/TGM-Plugin-Activation/blob/develop/example.php
 * 
 */
if ( ! function_exists( 'mpuniversal_register_required_plugins' ) ) {
    add_action( 'tgmpa_register', 'mpuniversal_register_required_plugins' );
    function mpuniversal_register_required_plugins() {
        $plugins = array(
            array(
                'name'         => 'Advanced Custom Fields Pro', // The plugin name.
                'slug'         => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
                'source'       => 'https://github.com/pronamic/advanced-custom-fields-pro/archive/refs/heads/main.zip', // The plugin source.
                'required'     => true, // If false, the plugin is only 'recommended' instead of required.
                'external_url' => 'https://github.com/pronamic/advanced-custom-fields-pro', // If set, overrides default API URL and points to an external URL.
            ),

            // This is an example of how to include a plugin from a GitHub repository in your theme.
            // This presumes that the plugin code is based in the root of the GitHub repository
            // and not in a subdirectory ('/src') of the repository.
            // array(
            //     'name'      => 'Carbon Fields',
            //     'slug'      => 'carbon-fields',
            //     'source'    => 'https://carbonfields.net/zip/latest/',
            //     // 'required'  => true, // OR false
            // ),

            // This is an example of how to include a plugin from the WordPress Plugin Repository.
            // array(
            //     'name'      => 'Advanced Custom Fields',
            //     'slug'      => 'advanced-custom-fields',
            //     'required'  => true, // OR false
            //     'version'   => '6.0.7',
            // ),

            // This is an example of the use of 'is_callable' functionality. A user could - for instance -
            // have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
            // 'wordpress-seo-premium'.
            // By setting 'is_callable' to either a function from that plugin or a class method
            // `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
            // recognize the plugin as being installed.
            array(
                'name'        => 'WordPress SEO by Yoast',
                'slug'        => 'wordpress-seo',
                'is_callable' => 'wpseo_init',
            ),

        );

        $config = array(
            'id'           => MPUNIVERSAL_TEXT_DOMAIN,             // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php',            // OR plugins.php Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );

        tgmpa( $plugins, $config );
    }
}