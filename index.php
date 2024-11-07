<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/local/theme_editor/lib.php');  // Include the lib.php file

// Ensure the user is logged in and has the necessary permissions
require_login();
$context = context_system::instance();
require_capability('local/theme_editor:view', $context);

// Set up the page
$PAGE->set_url(new moodle_url('/local/theme_editor/index.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_theme_editor'));
$PAGE->set_heading(get_string('pluginname', 'local_theme_editor'));

// Tabs setup
$tabs = [
    'general' => get_string('generalsettings', 'local_theme_editor'),
    'header' => get_string('headersettings', 'local_theme_editor'),
    'footer' => get_string('footersettings', 'local_theme_editor'),
    'frontpage' => get_string('frontpagesettings', 'local_theme_editor'),
    'frontpagebenefit' => get_string('frontpagebenefitsettings', 'local_theme_editor'),
    'frontpagefeatured' => get_string('frontpagefeaturedsettings', 'local_theme_editor'),
    'frontpagepromo' => get_string('frontpagepromosettings', 'local_theme_editor'),
    'frontpagelogo' => get_string('frontpagelogosettings', 'local_theme_editor'),
    'frontpagecategory' => get_string('frontpagecategorysettings', 'local_theme_editor'),
    'frontpageteacher' => get_string('frontpageteachersettings', 'local_theme_editor'),
    'frontpagetestimonial' => get_string('frontpagetestimonialsettings', 'local_theme_editor'),
    'frontpagefaq' => get_string('frontpagefaqsettings', 'local_theme_editor'),
    'frontpagectasection' => get_string('frontpagectasectionsettings', 'local_theme_editor'),
    'coursesetting' => get_string('coursesetting', 'local_theme_editor'),
    'socialmedia' => get_string('socialmediasetting', 'local_theme_editor'),
    'loginpage' => get_string('loginpagesetting', 'local_theme_editor'),
    'advance' => get_string('advancesetting', 'local_theme_editor'),
];

// Set up the current tab, defaulting to 'general'
$currenttab = optional_param('tab', 'general', PARAM_ALPHA);

// Display the tabs
$tabrows = [];
foreach ($tabs as $tabkey => $tabname) {
    $tabrows[] = new tabobject($tabkey, new moodle_url('/local/theme_editor/index.php', ['tab' => $tabkey]), $tabname);
}
echo $OUTPUT->header();
echo $OUTPUT->tabtree($tabrows, $currenttab);

// Settings Manager Class for organizing settings
class ThemeSettingsManager {
    private $settings = [
        'general' => [
            'sitename' => 'Site Name',
            'adminemail' => 'Admin Email'
        ],
        'header' => [
            'headerlogo' => 'Header Logo',
            'headercolor' => 'Header Color',
            'useheadersocial' => 'Use Header Social Links' // Checkbox setting for header social links
        ],
        'footer' => [
            'footermessage' => 'Footer Message',
            'footerlink' => 'Footer Link'
        ],
        'frontpage' => [
            'useheroslideshow' => 'Use Hero Slideshow' // Checkbox setting for frontpage slideshow
        ]
    ];

    private $checkbox_settings = ['useheadersocial', 'useheroslideshow']; // Define which settings are checkboxes

    // Get the settings for a specific category (tab)
    public function get_settings($tab) {
        return isset($this->settings[$tab]) ? $this->settings[$tab] : [];
    }

    // Check if a setting should be displayed as a checkbox
    public function is_checkbox($name) {
        return in_array($name, $this->checkbox_settings);
    }

    // Get a specific setting value
    public function get($tab, $name) {
        return get_config('theme_maker', $name);
    }

    // Save a setting value
    public function save($tab, $name, $value) {
        set_config($name, $value, 'theme_maker');
    }
}

// Instantiate the settings manager object
$settingsManager = new ThemeSettingsManager();

// Handle form submission for saving settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && confirm_sesskey()) {
    foreach ($settingsManager->get_settings($currenttab) as $name => $label) {
        if ($settingsManager->is_checkbox($name)) {
            // Checkbox value (1 if checked, 0 if not)
            $value = optional_param($name, null, PARAM_BOOL) ? 1 : 0;
        } else {
            // Text field value
            $value = optional_param($name, '', PARAM_TEXT);
        }

        // Save the setting using the defined plugin scope
        $settingsManager->save($currenttab, $name, $value);
        \core\notification::add(get_string('contentsaved', 'local_theme_editor', $label), \core\output\notification::NOTIFY_SUCCESS);
    }
}

// Display the form for the current tab
echo '<form method="post">';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';

foreach ($settingsManager->get_settings($currenttab) as $name => $label) {
    $value = $settingsManager->get($currenttab, $name);

    if ($settingsManager->is_checkbox($name)) {
        // Render checkbox for boolean settings
        $checked = $value ? 'checked' : '';
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="1" ' . $checked . '><br><br>';
    } else {
        // Render text input for other settings
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<input type="text" name="' . $name . '" id="' . $name . '" value="' . s($value) . '"><br><br>';
    }
}

echo '<button type="submit">' . get_string('savechanges') . '</button>';
echo '</form>';

echo $OUTPUT->footer();