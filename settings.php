<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $context = context_system::instance();
    
    // Only show settings page if the user has 'local/theme_editor:edit' capability.
    if (has_capability('local/theme_editor:edit', $context)) {
        
        // Define the settings page for the plugin.
        $settings = new admin_settingpage('local_theme_editor', get_string('pluginname', 'local_theme_editor'));
        
        // Add a textarea setting for custom CSS.
        $settings->add(new admin_setting_configtextarea(
            'theme_moove/numbersfrontpagecontent', 
            get_string('customcss', 'local_theme_editor'), // Title.
            get_string('customcss_desc', 'local_theme_editor'), // Description.
            '', 
            PARAM_RAW,
            '50', 
            '8' 
        ));

        // Add the settings page under local plugins.
        $ADMIN->add('localplugins', $settings);
    }
}