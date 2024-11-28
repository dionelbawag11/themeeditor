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
    'frontpageslideshow' => get_string('frontpagesettings', 'local_theme_editor'),
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
class ThemeSettingsManager
{
    private $settings = [
        'general' => [
            'logo' => 'Site logo', //upload
            'preset' => 'Theme colour scheme', //dropdown with choices
            'brandcolorprimary' => 'Primary Color', //hex colors
            'brandcolorsecondary' => 'Secondary Color', //hex colors 
            'usefontapi' => 'Enable Google Fonts API', //checkbox
            'pagefont' => 'Page Font', //dropdown with choices
            'headingfont' => 'Heading Font', //dropdown with choices
        ],
        'header' => [
            'useheadersocial' => 'Use Header Social Links', //checkbox
            'useheaderbranding' => 'Hide site logo and custom menu section', //checkbox
            'usealert' => 'Enable alert', //checkbox 
            'alertcontent' => 'Alert content', //text field
            'alertbgcolor' => 'Alert colour', //hex colors
        ],
        'footer' => [
            'usefootersocial' => 'Enable social media links', //checkbox
            'footersocialsectiontitle' => 'Social media heading', //text field
            'usefooterblocks' => 'Enable footer content blocks', //checkbox
            'footerblock1' => 'Footer Block 1', //text field big
            'footerblock2' => 'Footer Block 2', //text field big
            'footerblock3' => 'Footer Block 3', //text field big
            'footerblock4' => 'Footer Block 4', //text field big
            'usefooterwidget' => 'Enable footer widget', //checkbox
            'footerwidgettitle' => 'Title', //textfield
            'footerwidget' => 'Content', //text field big
            'copyright' => 'Copyright', //text field big

        ],
        'frontpageslideshow' => [
            'useheroslideshow' => 'Enable hero slideshow', // Checkbox setting for frontpage slideshow
            'usesearch' => 'Course search box', //checkbox
            'slideshowheight' => 'Slideshow height', //dropdown options of height
            'heroheadline' => 'Site headline', //text field big
            'herosummary' => 'Site summary', //text field big
            'herocta' => 'CTA button text', //textfield
            'herourl' => 'CTA button link', //textfield
            'herourlopennew' => 'Open link in a new window/tab', //checkbox
            'useherovideo' => 'Enable video link', //checkbox
            'herovideo' => 'Video link text', //textfield
            'herovideoswitcher' => 'Video type switcher', //dropdown of options
            'herovideoid' => 'Video ID', //textfield
            'slide1image' => 'Slide image 1', //upload photo
            'slide2image' => 'Slide image 2', //upload photo
            'slide3image' => 'Slide image 3', //upload photo
            'slide4image' => 'Slide image 4', //upload photo
            'slide5image' => 'Slide image 5', //upload photo
            'slide6image' => 'Slide image 6', //upload photo
            'slide7image' => 'Slide image 7', //upload photo
            'slide8image' => 'Slide image 8', //upload photo
            'slide9image' => 'Slide image 9', //upload photo
            'slide10image' => 'Slide image 10', //upload photo
        ],
        'frontpagebenefit' => [
            'usebenefits' => 'Enable benefits section', //checkbox
            'benefitsbuttontext' => 'CTA button text', //textfield
            'benefitsbuttonurl' => 'CTA button link', //textfield
            'benefitsbuttonurlopennew' => 'Open link in a new window/tab', //checkbox
            'benefit1icon' => 'Icon 1', //textfield
            'usebenefit1image' => 'Use Image 1', //checkbox
            'benefit1image' => 'Image 1', //upload
            'benefit1title' => 'Title 1', //textfield
            'benefit1content' => 'Content 1', //textfield big
            'benefit2icon' => 'Icon 2', //textfield
            'usebenefit2image' => 'Use Image 2', //checkbox
            'benefit2image' => 'Image 2', //upload
            'benefit2title' => 'Title 2', //textfield
            'benefit2content' => 'Content 2', //textfield big
            'benefit3icon' => 'Icon 3', //textfield
            'usebenefit3image' => 'Use Image 3', //checkbox
            'benefit3image' => 'Image 3', //upload
            'benefit3title' => 'Title 3', //textfield
            'benefit3content' => 'Content 3', //textfield big
            'benefit4icon' => 'Icon 4', //textfield
            'usebenefit4image' => 'Use Image 4', //checkbox
            'benefit4image' => 'Image 4', //upload
            'benefit4title' => 'Title 4', //textfield
            'benefit4content' => 'Content 4', //textfield big
            'benefit5icon' => 'Icon 5', //textfield
            'usebenefit5image' => 'Use Image 5', //checkbox
            'benefit5image' => 'Image 5', //upload
            'benefit5title' => 'Title 5', //textfield
            'benefit5content' => 'Content 5', //textfield big
            'benefit6icon' => 'Icon 6', //textfield
            'usebenefit6image' => 'Use Image 6', //checkbox
            'benefit6image' => 'Image 6', //upload
            'benefit6title' => 'Title 6', //textfield
            'benefit6content' => 'Content 6', //textfield big 
        ],
        'frontpagefeatured' => [
            'usehomeblocks' => 'Enable featured section',
            'featuredsectiontitle' => 'Featured section title',
            'homeblockheight' => 'Block height',

            'homeblock1title' => 'Title 1',
            'homeblock1image' => 'Image 1',
            'homeblock1content' => 'Content 1',
            'homeblock1url' => 'Target Url 1',
            'homeblock1urlopennew' => 'Open link in a new window/tab',
            'homeblock1label' => 'Label 1',

            'homeblock2title' => 'Title 2',
            'homeblock2image' => 'Image 2',
            'homeblock2content' => 'Content 2',
            'homeblock2url' => 'Target Url 2',
            'homeblock2urlopennew' => 'Open link in a new window/tab',
            'homeblock2label' => 'Label 2',

            'homeblock3title' => 'Title 3',
            'homeblock3image' => 'Image 3',
            'homeblock3content' => 'Content 3',
            'homeblock3url' => 'Target Url 3',
            'homeblock3urlopennew' => 'Open link in a new window/tab',
            'homeblock3label' => 'Label 3',

            'homeblock4title' => 'Title 4',
            'homeblock4image' => 'Image 4',
            'homeblock4content' => 'Content 4',
            'homeblock4url' => 'Target Url 4',
            'homeblock4urlopennew' => 'Open link in a new window/tab',
            'homeblock4label' => 'Label 4',

            'homeblock5title' => 'Title 5',
            'homeblock5image' => 'Image 5',
            'homeblock5content' => 'Content 5',
            'homeblock5url' => 'Target Url 5',
            'homeblock5urlopennew' => 'Open link in a new window/tab',
            'homeblock5label' => 'Label 5',

            'homeblock6title' => 'Title 6',
            'homeblock6image' => 'Image 6',
            'homeblock6content' => 'Content 6',
            'homeblock6url' => 'Target Url 6',
            'homeblock6urlopennew' => 'Open link in a new window/tab',
            'homeblock6label' => 'Label 6',

            'homeblock7title' => 'Title 7',
            'homeblock7image' => 'Image 7',
            'homeblock7content' => 'Content 7',
            'homeblock7url' => 'Target Url 7',
            'homeblock7urlopennew' => 'Open link in a new window/tab',
            'homeblock7label' => 'Label 7',

            'homeblock8title' => 'Title 8',
            'homeblock8image' => 'Image 8',
            'homeblock8content' => 'Content 8',
            'homeblock8url' => 'Target Url 8',
            'homeblock8urlopennew' => 'Open link in a new window/tab',
            'homeblock8label' => 'Label 8',

            'homeblock9title' => 'Title 9',
            'homeblock9image' => 'Image 9',
            'homeblock9content' => 'Content 9',
            'homeblock9url' => 'Target Url 9',
            'homeblock9urlopennew' => 'Open link in a new window/tab',
            'homeblock9label' => 'Label 9',

            'homeblock10title' => 'Title 10',
            'homeblock10image' => 'Image 10',
            'homeblock10content' => 'Content 10',
            'homeblock10url' => 'Target Url 10',
            'homeblock10urlopennew' => 'Open link in a new window/tab',
            'homeblock10label' => 'Label 10',

            'homeblock11title' => 'Title 11',
            'homeblock11image' => 'Image 11',
            'homeblock11content' => 'Content 11',
            'homeblock11url' => 'Target Url 11',
            'homeblock11urlopennew' => 'Open link in a new window/tab',
            'homeblock11label' => 'Label 11',

            'homeblock12title' => 'Title 12',
            'homeblock12image' => 'Image 12',
            'homeblock12content' => 'Content 12',
            'homeblock12url' => 'Target Url 12',
            'homeblock12urlopennew' => 'Open link in a new window/tab',
            'homeblock12label' => 'Label 12',

            'homeblock13title' => 'Title 13',
            'homeblock13image' => 'Image 13',
            'homeblock13content' => 'Content 13',
            'homeblock13url' => 'Target Url 13',
            'homeblock13urlopennew' => 'Open link in a new window/tab',
            'homeblock13label' => 'Label 13',

            'homeblock14title' => 'Title 14',
            'homeblock14image' => 'Image 14',
            'homeblock14content' => 'Content 14',
            'homeblock14url' => 'Target Url 14',
            'homeblock14urlopennew' => 'Open link in a new window/tab',
            'homeblock14label' => 'Label 14',

            'homeblock15title' => 'Title 15',
            'homeblock15image' => 'Image 15',
            'homeblock15content' => 'Content 15',
            'homeblock15url' => 'Target Url 15',
            'homeblock15urlopennew' => 'Open link in a new window/tab',
            'homeblock15label' => 'Label 15',

            'homeblock16title' => 'Title 16',
            'homeblock16image' => 'Image 16',
            'homeblock16content' => 'Content 16',
            'homeblock16url' => 'Target Url 16',
            'homeblock16urlopennew' => 'Open link in a new window/tab',
            'homeblock16label' => 'Label 16',

            'homeblock17title' => 'Title 17',
            'homeblock17image' => 'Image 17',
            'homeblock17content' => 'Content 17',
            'homeblock17url' => 'Target Url 17',
            'homeblock17urlopennew' => 'Open link in a new window/tab',
            'homeblock17label' => 'Label 17',

            'homeblock18title' => 'Title 18',
            'homeblock18image' => 'Image 18',
            'homeblock18content' => 'Content 18',
            'homeblock18url' => 'Target Url 18',
            'homeblock18urlopennew' => 'Open link in a new window/tab',
            'homeblock18label' => 'Label 18',

            'homeblock19title' => 'Title 19',
            'homeblock19image' => 'Image 19',
            'homeblock19content' => 'Content 19',
            'homeblock19url' => 'Target Url 19',
            'homeblock19urlopennew' => 'Open link in a new window/tab',
            'homeblock19label' => 'Label 19',

            'homeblock20title' => 'Title 20',
            'homeblock20image' => 'Image 20',
            'homeblock20content' => 'Content 20',
            'homeblock20url' => 'Target Url 20',
            'homeblock20urlopennew' => 'Open link in a new window/tab',
            'homeblock20label' => 'Label 20',


        ],
        'frontpagepromo' => [
            'usepromocarousel' => '',
            'carouselitemheight' => '',

            'carouselitem1' => 'Title 1',
            'carouselitem1image' => 'Image 1',
            'carouselitem1content' => 'Content 1',
            'carouselitem1buttontext' => 'CTA button text 1',
            'carouselitem1buttonurl' => 'CTA button link 1',
            'carouselitem1buttonurlopennew' => 'Open link in a new window/tab',
            'usecarouselitem1video' => 'Enable video for this carousel item 1',
            'carouselitem1videoswitcher' => 'Video type switcher 1',
            'carouselitem1videoid' => 'Video ID 1',

            'carouselitem2' => 'Title 2',
            'carouselitem2image' => 'Image 2',
            'carouselitem2content' => 'Content 2',
            'carouselitem2buttontext' => 'CTA button text 2',
            'carouselitem2buttonurl' => 'CTA button link 2',
            'carouselitem2buttonurlopennew' => 'Open link in a new window/tab',
            'usecarouselitem2video' => 'Enable video for this carousel item 2',
            'carouselitem2videoswitcher' => 'Video type switcher 2',
            'carouselitem2videoid' => 'Video ID 2',

            'carouselitem3' => 'Title 3',
            'carouselitem3image' => 'Image 3',
            'carouselitem3content' => 'Content 3',
            'carouselitem3buttontext' => 'CTA button text 3',
            'carouselitem3buttonurl' => 'CTA button link 3',
            'carouselitem3buttonurlopennew' => 'Open link in a new window/tab',
            'usecarouselitem3video' => 'Enable video for this carousel item 3',
            'carouselitem3videoswitcher' => 'Video type switcher 3',
            'carouselitem3videoid' => 'Video ID 3',

            'carouselitem4' => 'Title 4',
            'carouselitem4image' => 'Image 4',
            'carouselitem4content' => 'Content 4',
            'carouselitem4buttontext' => 'CTA button text 4',
            'carouselitem4buttonurl' => 'CTA button link 4',
            'carouselitem4buttonurlopennew' => 'Open link in a new window/tab',
            'usecarouselitem4video' => 'Enable video for this carousel item 4',
            'carouselitem4videoswitcher' => 'Video type switcher 4',
            'carouselitem4videoid' => 'Video ID 4',

            'carouselitem5' => 'Title 5',
            'carouselitem5image' => 'Image 5',
            'carouselitem5content' => 'Content 5',
            'carouselitem5buttontext' => 'CTA button text 5',
            'carouselitem5buttonurl' => 'CTA button link 5',
            'carouselitem5buttonurlopennew' => 'Open link in a new window/tab',
            'usecarouselitem5video' => 'Enable video for this carousel item 5',
            'carouselitem5videoswitcher' => 'Video type switcher 5',
            'carouselitem5videoid' => 'Video ID 5',

            'carouselitem6' => 'Title 6',
            'carouselitem6image' => 'Image 6',
            'carouselitem6content' => 'Content 6',
            'carouselitem6buttontext' => 'CTA button text 6',
            'carouselitem6buttonurl' => 'CTA button link 6',
            'carouselitem6buttonurlopennew' => 'Open link in a new window/tab',
            'usecarouselitem6video' => 'Enable video for this carousel item 6',
            'carouselitem6videoswitcher' => 'Video type switcher 6',
            'carouselitem6videoid' => 'Video ID 6',
        ],
        'frontpagelogo' => [],
        'frontpagecategory' => [],
        'frontpageteacher' => [],
        'frontpagetestimonial' => [],
        'frontpagefaq' => [],
        'frontpagectasection' => [],
        'coursesetting' => [],
        'socialmedia' => [],
        'loginpage' => [],
        'advance' => [],

    ];

    private $checkbox_settings = ['useheadersocial', 'useheroslideshow'];
    public function get_settings($tab)
    {
        return isset($this->settings[$tab]) ? $this->settings[$tab] : [];
    }

    public function is_checkbox($name)
    {
        return in_array($name, $this->checkbox_settings);
    }

    public function get($tab, $name)
    {
        return get_config('theme_maker', $name);
    }

    public function save($tab, $name, $value)
    {
        set_config($name, $value, 'theme_maker');
    }
}

$settingsManager = new ThemeSettingsManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && confirm_sesskey()) {
    foreach ($settingsManager->get_settings($currenttab) as $name => $label) {
        if ($settingsManager->is_checkbox($name)) {
            $value = optional_param($name, null, PARAM_BOOL) ? 1 : 0;
        } else {
            $value = optional_param($name, '', PARAM_TEXT);
        }

        $settingsManager->save($currenttab, $name, $value);
    }
    \core\notification::add(get_string('contentsaved', 'local_theme_editor', $label), \core\output\notification::NOTIFY_SUCCESS);
    theme_reset_all_caches();
    \core\notification::add(get_string('cachepurged', 'local_theme_editor'), \core\output\notification::NOTIFY_INFO);
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