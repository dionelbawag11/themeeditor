<?php
// classes/form/theme_settings_form.php
namespace local_themeeditor\form;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class theme_settings_form extends \moodleform {
    protected function definition() {
        $mform = $this->_form;

        // Get the active theme.
        $currenttheme = \theme_config::current_theme();
        $themename = $currenttheme->name;

        // Display current theme settings.
        $settings = get_config($themename);
        foreach ($settings as $name => $value) {
            $mform->addElement('text', $name, get_string($name, $themename), ['value' => $value]);
            $mform->setType($name, PARAM_RAW);
        }

        // Option to purge caches.
        $mform->addElement('advcheckbox', 'purgecaches', get_string('purgecaches', 'local_themeeditor'));
        $mform->setType('purgecaches', PARAM_BOOL);

        // Add a submit button.
        $mform->addElement('submit', 'submitbutton', get_string('savechanges'));
    }
}
