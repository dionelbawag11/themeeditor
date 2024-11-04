<?php
// index.php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__ . '/classes/form/theme_settings_form.php');

require_login();
admin_externalpage_setup('local_themeeditor');

// Check capability for manager role.
$context = context_system::instance();
require_capability('local/themeeditor:edit', $context);

// Load the form.
$mform = new \local_themeeditor\form\theme_settings_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/themeeditor/index.php'));
} elseif ($data = $mform->get_data()) {
    // Save settings.
    $currenttheme = \theme_config::current_theme();
    $themename = $currenttheme->name;
    
    foreach ($data as $name => $value) {
        if ($name !== 'purgecaches') {
            set_config($name, $value, $themename);
        }
    }

    // Purge caches if selected.
    if (!empty($data->purgecaches)) {
        purge_all_caches();
        \core\notification::add(get_string('cachespurged', 'local_themeeditor'), \core\output\notification::NOTIFY_SUCCESS);
    }

    redirect(new moodle_url('/local/themeeditor/index.php'));
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'local_themeeditor') . ': ' . ucfirst(\theme_config::current_theme()->name));
$mform->display();
echo $OUTPUT->footer();
