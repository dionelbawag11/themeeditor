<?php
// settings.php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_themeeditor', get_string('pluginname', 'local_themeeditor'));

    $ADMIN->add('localplugins', $settings);
}
