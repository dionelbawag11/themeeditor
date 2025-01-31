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
            'usepromocarousel' => 'Enable promo carousel section',
            'carouselitemheight' => 'Carousel item height',

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
        'frontpagelogo' => [
            'uselogos' => 'Enable logos section',
            'logossectiontitle' => 'Logos section title',

            'logo1image' => 'Image 1',
            'logo1alttext' => 'Alternative text 1',
            'logo1url' => 'Logo link 1',

            'logo2image' => 'Image 2',
            'logo2alttext' => 'Alternative text 2',
            'logo2url' => 'Logo link 2',

            'logo3image' => 'Image 3',
            'logo3alttext' => 'Alternative text 3',
            'logo3url' => 'Logo link 3',

            'logo4image' => 'Image 4',
            'logo4alttext' => 'Alternative text 4',
            'logo4url' => 'Logo link 4',

            'logo5image' => 'Image 5',
            'logo5alttext' => 'Alternative text 5',
            'logo5url' => 'Logo link 5',

            'logo6image' => 'Image 6',
            'logo6alttext' => 'Alternative text 6',
            'logo6url' => 'Logo link 6',
        ],
        'frontpagecategory' => [
            'usecategories' => 'Enable categories section',
            'categoriessectiontitle' => 'Categories section title',
            'categoriesbuttontext' => 'CTA button text',
            'categoriesbuttonurl' => 'CTA button link',
            'categoriesbuttonurlopennew' => 'Open link in a new window/tab',

            'category1title' => 'Title 1',
            'category1image' => 'Image 1',
            'category1content' => 'Content 1',
            'category1url' => 'Target URL 1',

            'category2title' => 'Title 2',
            'category2image' => 'Image 2',
            'category2content' => 'Content 2',
            'category2url' => 'Target URL 2',

            'category3title' => 'Title 3',
            'category3image' => 'Image 3',
            'category3content' => 'Content 3',
            'category3url' => 'Target URL 3',

            'category4title' => 'Title 4',
            'category4image' => 'Image 4',
            'category4content' => 'Content 4',
            'category4url' => 'Target URL 4',

            'category5title' => 'Title 5',
            'category5image' => 'Image 5',
            'category5content' => 'Content 5',
            'category5url' => 'Target URL 5',

            'category6title' => 'Title 6',
            'category6image' => 'Image 6',
            'category6content' => 'Content 6',
            'category6url' => 'Target URL 6',

            'category7title' => 'Title 7',
            'category7image' => 'Image 7',
            'category7content' => 'Content 7',
            'category7url' => 'Target URL 7',

            'category8title' => 'Title 8',
            'category8image' => 'Image 8',
            'category8content' => 'Content 8',
            'category8url' => 'Target URL 8',

            'category9title' => 'Title 9',
            'category9image' => 'Image 9',
            'category9content' => 'Content 9',
            'category9url' => 'Target URL 9',

            'category10title' => 'Title 10',
            'category10image' => 'Image 10',
            'category10content' => 'Content 10',
            'category10url' => 'Target URL 10',

            'category11title' => 'Title 11',
            'category11image' => 'Image 11',
            'category11content' => 'Content 11',
            'category11url' => 'Target URL 11',

            'category12title' => 'Title 12',
            'category12image' => 'Image 12',
            'category12content' => 'Content 12',
            'category12url' => 'Target URL 12',

            'category13title' => 'Title 13',
            'category13image' => 'Image 13',
            'category13content' => 'Content 13',
            'category13url' => 'Target URL 13',

            'category14title' => 'Title 14',
            'category14image' => 'Image 14',
            'category14content' => 'Content 14',
            'category14url' => 'Target URL 14',

            'category15title' => 'Title 15',
            'category15image' => 'Image 15',
            'category15content' => 'Content 15',
            'category15url' => 'Target URL 15',

            'category16title' => 'Title 16',
            'category16image' => 'Image 16',
            'category16content' => 'Content 16',
            'category16url' => 'Target URL 16',

            'category17title' => 'Title 17',
            'category17image' => 'Image 17',
            'category17content' => 'Content 17',
            'category17url' => 'Target URL 17',

            'category18title' => 'Title 18',
            'category18image' => 'Image 18',
            'category18content' => 'Content 18',
            'category18url' => 'Target URL 18',

            'category19title' => 'Title 19',
            'category19image' => 'Image 19',
            'category19content' => 'Content 19',
            'category19url' => 'Target URL 19',

            'category20title' => 'Title 20',
            'category20image' => 'Image 20',
            'category20content' => 'Content 20',
            'category20url' => 'Target URL 20',

        ],
        'frontpageteacher' => [
            'useteachers' => 'Enable teachers section',
            'teachersectiontitle' => 'Teachers section title',
            'teachersbuttontext' => 'CTA button text',
            'teachersbuttonurl' => 'CTA button link',
            'teachersbuttonurlopennew' => 'Open link in a new window/tab',
            'teacher1image' => 'Image 1',
            'teacher1name' => 'Name 1',
            'teacher1meta' => 'Title 1',
            'teacher1content' => 'Bio 1',

            'teacher2image' => 'Image 2',
            'teacher2name' => 'Name 2',
            'teacher2meta' => 'Title 2',
            'teacher2content' => 'Bio 2',

            'teacher3image' => 'Image 3',
            'teacher3name' => 'Name 3',
            'teacher3meta' => 'Title 3',
            'teacher3content' => 'Bio 3',

            'teacher4image' => 'Image 4',
            'teacher4name' => 'Name 4',
            'teacher4meta' => 'Title 4',
            'teacher4content' => 'Bio 4',

            'teacher5image' => 'Image 5',
            'teacher5name' => 'Name 5',
            'teacher5meta' => 'Title 5',
            'teacher5content' => 'Bio 5',

            'teacher6image' => 'Image 6',
            'teacher6name' => 'Name 6',
            'teacher6meta' => 'Title 6',
            'teacher6content' => 'Bio 6',

            'teacher7image' => 'Image 7',
            'teacher7name' => 'Name 7',
            'teacher7meta' => 'Title 7',
            'teacher7content' => 'Bio 7',

            'teacher8image' => 'Image 8',
            'teacher8name' => 'Name 8',
            'teacher8meta' => 'Title 8',
            'teacher8content' => 'Bio 8',

            'teacher9image' => 'Image 9',
            'teacher9name' => 'Name 9',
            'teacher9meta' => 'Title 9',
            'teacher9content' => 'Bio 9',

            'teacher10image' => 'Image 10',
            'teacher10name' => 'Name 10',
            'teacher10meta' => 'Title 10',
            'teacher10content' => 'Bio 10',

            'teacher11image' => 'Image 11',
            'teacher11name' => 'Name 11',
            'teacher11meta' => 'Title 11',
            'teacher11content' => 'Bio 11',

            'teacher12image' => 'Image 12',
            'teacher12name' => 'Name 12',
            'teacher12meta' => 'Title 12',
            'teacher12content' => 'Bio 12',

            'teacher13image' => 'Image 13',
            'teacher13name' => 'Name 13',
            'teacher13meta' => 'Title 13',
            'teacher13content' => 'Bio 13',

            'teacher14image' => 'Image 14',
            'teacher14name' => 'Name 14',
            'teacher14meta' => 'Title 14',
            'teacher14content' => 'Bio 14',

            'teacher15image' => 'Image 15',
            'teacher15name' => 'Name 15',
            'teacher15meta' => 'Title 15',
            'teacher15content' => 'Bio 15',

            'teacher16image' => 'Image 16',
            'teacher16name' => 'Name 16',
            'teacher16meta' => 'Title 16',
            'teacher16content' => 'Bio 16',

            'teacher17image' => 'Image 17',
            'teacher17name' => 'Name 17',
            'teacher17meta' => 'Title 17',
            'teacher17content' => 'Bio 17',

            'teacher18image' => 'Image 18',
            'teacher18name' => 'Name 18',
            'teacher18meta' => 'Title 18',
            'teacher18content' => 'Bio 18',

            'teacher19image' => 'Image 19',
            'teacher19name' => 'Name 19',
            'teacher19meta' => 'Title 19',
            'teacher19content' => 'Bio 19',

            'teacher20image' => 'Image 20',
            'teacher20name' => 'Name 20',
            'teacher20meta' => 'Title 20',
            'teacher20content' => 'Bio 20',
        ],
        'frontpagetestimonial' => [
            'usetestimonials' => 'Enable testimonials',
            'testimonialsectiontitle' => 'Testimonials section title',
            'testimonialitemheight' => 'Testimonial item height',
            'testimonialsbuttontext' => 'CTA button text',
            'testimonialsbuttonurl' => 'CTA button link',
            'testimonialsbuttonurlopennew' => 'Open link in a new window/tab',

            'testimonial1image' => 'Image 1',
            'testimonial1name' => 'Source name 1',
            'testimonial1meta' => 'Source meta data 1',
            'testimonial1content' => 'Testimonial quote 1',

            'testimonial2image' => 'Image 2',
            'testimonial2name' => 'Source name 2',
            'testimonial2meta' => 'Source meta data 2',
            'testimonial2content' => 'Testimonial quote 2',

            'testimonial3image' => 'Image 3',
            'testimonial3name' => 'Source name 3',
            'testimonial3meta' => 'Source meta data 3',
            'testimonial3content' => 'Testimonial quote 3',

            'testimonial4image' => 'Image 4',
            'testimonial4name' => 'Source name 4',
            'testimonial4meta' => 'Source meta data 4',
            'testimonial4content' => 'Testimonial quote 4',

            'testimonial5image' => 'Image 5',
            'testimonial5name' => 'Source name 5',
            'testimonial5meta' => 'Source meta data 5',
            'testimonial5content' => 'Testimonial quote 5',

            'testimonial6image' => 'Image 6',
            'testimonial6name' => 'Source name 6',
            'testimonial6meta' => 'Source meta data 6',
            'testimonial6content' => 'Testimonial quote 6',

        ],
        'frontpagefaq' => [
            'usefaq' => 'Enable FAQ section',
            'faqsectiontitle' => 'FAQ section title',

            'faq1title' => 'Question 1',
            'faq1content' => 'Answer 1',

            'faq2title' => 'Question 2',
            'faq2content' => 'Answer 2',

            'faq3title' => 'Question 3',
            'faq3content' => 'Answer 3',

            'faq4title' => 'Question 4',
            'faq4content' => 'Answer 4',

            'faq5title' => 'Question 5',
            'faq5content' => 'Answer 5',

            'faq6title' => 'Question 6',
            'faq6content' => 'Answer 6',

            'faq7title' => 'Question 7',
            'faq7content' => 'Answer 7',

            'faq8title' => 'Question 8',
            'faq8content' => 'Answer 8',

            'faq9title' => 'Question 9',
            'faq9content' => 'Answer 9',

            'faq10title' => 'Question 10',
            'faq10content' => 'Answer 10',

            'faqsectionbuttontext' => 'CTA button text',
            'faqsectionbuttonurl' => 'CTA Button link',
            'faqsectionbuttonurlopennew' => 'Open link in a new window/tab',

        ],
        'frontpagectasection' => [
            'usectsection' => 'Enable CTA section',
            'ctasectiontitle' => 'CTA section title',
            'ctasectioncontent' => 'CTA section content',
            'ctasectionbuttontext' => 'CTA button text',
            'ctasectionbuttonurl' => 'CTA Button link',
            'ctasectionbuttonurlopennew' => 'Open link in a new window/tab',
            'usectadatabox' => 'Enable Data Box',
            'ctadataitem1title' => 'Data Box Item 1 Title',
            'ctadataitem1meta' => 'Data Box Item 1 Description',
            'ctadataitem2title' => 'Data Box Item 2 Title',
            'ctadataitem2meta' => 'Data Box Item 2 Description',
            'ctadataitem3title' => 'Data Box Item 3 Title',
            'ctadataitem3meta' => 'Data Box Item 3 Description',
            'ctadataitem4title' => 'Data Box Item 4 Title',
            'ctadataitem4meta' => 'Data Box Item 4 Description',
        ],
        'coursesetting' => [
            'coursedisplaystyle' => 'Course list layout style',
            'usecoursesummarytrim' => 'Truncate the course summary',
            'coursesummarylength' => 'Course summary max characters',
            'defaultcourseimage' => 'Default course image',
            'usecourseheaderimage' => 'Show course header image',
            'courseheaderimageheight' => 'Header image height',
        ],
        'dropdownmenu' => [
            'usedropdown' => 'Enable Dropdown Menu',
            'dropdownname' => 'Dropdown Menu Name',
            'dropdowncontentheading' => 'Dropdown Menu Content Heading',
            'dropdowncolnumber' => 'Dropdown Menu Number of Columns',
            'dropdownbuttontext' => 'CTA button text',
            'dropdownbuttonurl' => 'CTA button link',
            'dropdownbuttonurlopennew' => 'Open link in a new window/tab',
            'dropdownitem1title' => 'Menu Item Text 1',
            'dropdownitem1url' => 'Menu Item Link 1',
            'dropdownitem1opennew' => 'Open link in a new window/tab 1',

            'dropdownitem2title' => 'Menu Item Text 2',
            'dropdownitem2url' => 'Menu Item Link 2',
            'dropdownitem2opennew' => 'Open link in a new window/tab 2',

            'dropdownitem3title' => 'Menu Item Text 3',
            'dropdownitem3url' => 'Menu Item Link 3',
            'dropdownitem3opennew' => 'Open link in a new window/tab 3',

            'dropdownitem4title' => 'Menu Item Text 4',
            'dropdownitem4url' => 'Menu Item Link 4',
            'dropdownitem4opennew' => 'Open link in a new window/tab 4',

            'dropdownitem5title' => 'Menu Item Text 5',
            'dropdownitem5url' => 'Menu Item Link 5',
            'dropdownitem5opennew' => 'Open link in a new window/tab 5',

            'dropdownitem6title' => 'Menu Item Text 6',
            'dropdownitem6url' => 'Menu Item Link 6',
            'dropdownitem6opennew' => 'Open link in a new window/tab 6',

            'dropdownitem7title' => 'Menu Item Text 7',
            'dropdownitem7url' => 'Menu Item Link 7',
            'dropdownitem7opennew' => 'Open link in a new window/tab 7',

            'dropdownitem8title' => 'Menu Item Text 8',
            'dropdownitem8url' => 'Menu Item Link 8',
            'dropdownitem8opennew' => 'Open link in a new window/tab 8',

            'dropdownitem9title' => 'Menu Item Text 9',
            'dropdownitem9url' => 'Menu Item Link 9',
            'dropdownitem9opennew' => 'Open link in a new window/tab 9',

            'dropdownitem10title' => 'Menu Item Text 10',
            'dropdownitem10url' => 'Menu Item Link 10',
            'dropdownitem10opennew' => 'Open link in a new window/tab 10',

            'dropdownitem11title' => 'Menu Item Text 11',
            'dropdownitem11url' => 'Menu Item Link 11',
            'dropdownitem11opennew' => 'Open link in a new window/tab 11',

            'dropdownitem12title' => 'Menu Item Text 12',
            'dropdownitem12url' => 'Menu Item Link 12',
            'dropdownitem12opennew' => 'Open link in a new window/tab 12',

            'dropdownitem13title' => 'Menu Item Text 13',
            'dropdownitem13url' => 'Menu Item Link 13',
            'dropdownitem13opennew' => 'Open link in a new window/tab 13',

            'dropdownitem14title' => 'Menu Item Text 14',
            'dropdownitem14url' => 'Menu Item Link 14',
            'dropdownitem14opennew' => 'Open link in a new window/tab 14',

            'dropdownitem15title' => 'Menu Item Text 15',
            'dropdownitem15url' => 'Menu Item Link 15',
            'dropdownitem15opennew' => 'Open link in a new window/tab 15',

            'dropdownitem16title' => 'Menu Item Text 16',
            'dropdownitem16url' => 'Menu Item Link 16',
            'dropdownitem16opennew' => 'Open link in a new window/tab 16',

            'dropdownitem17title' => 'Menu Item Text 17',
            'dropdownitem17url' => 'Menu Item Link 17',
            'dropdownitem17opennew' => 'Open link in a new window/tab 17',

            'dropdownitem18title' => 'Menu Item Text 18',
            'dropdownitem18url' => 'Menu Item Link 18',
            'dropdownitem18opennew' => 'Open link in a new window/tab 18',

            'dropdownitem19title' => 'Menu Item Text 19',
            'dropdownitem19url' => 'Menu Item Link 19',
            'dropdownitem19opennew' => 'Open link in a new window/tab 19',

            'dropdownitem20title' => 'Menu Item Text 20',
            'dropdownitem20url' => 'Menu Item Link 20',
            'dropdownitem20opennew' => 'Open link in a new window/tab 20',

            'dropdownitem21title' => 'Menu Item Text 21',
            'dropdownitem21url' => 'Menu Item Link 21',
            'dropdownitem21opennew' => 'Open link in a new window/tab 21',

            'dropdownitem22title' => 'Menu Item Text 22',
            'dropdownitem22url' => 'Menu Item Link 22',
            'dropdownitem22opennew' => 'Open link in a new window/tab 22',

            'dropdownitem23title' => 'Menu Item Text 23',
            'dropdownitem23url' => 'Menu Item Link 23',
            'dropdownitem23opennew' => 'Open link in a new window/tab 23',

            'dropdownitem24title' => 'Menu Item Text 24',
            'dropdownitem24url' => 'Menu Item Link 24',
            'dropdownitem24opennew' => 'Open link in a new window/tab 24',

            'dropdownitem25title' => 'Menu Item Text 25',
            'dropdownitem25url' => 'Menu Item Link 25',
            'dropdownitem25opennew' => 'Open link in a new window/tab 25',

            'dropdownitem26title' => 'Menu Item Text 26',
            'dropdownitem26url' => 'Menu Item Link 26',
            'dropdownitem26opennew' => 'Open link in a new window/tab 26',

            'dropdownitem27title' => 'Menu Item Text 27',
            'dropdownitem27url' => 'Menu Item Link 27',
            'dropdownitem27opennew' => 'Open link in a new window/tab 27',

            'dropdownitem28title' => 'Menu Item Text 28',
            'dropdownitem28url' => 'Menu Item Link 28',
            'dropdownitem28opennew' => 'Open link in a new window/tab 28',

            'dropdownitem29title' => 'Menu Item Text 29',
            'dropdownitem29url' => 'Menu Item Link 29',
            'dropdownitem29opennew' => 'Open link in a new window/tab 29',

            'dropdownitem30title' => 'Menu Item Text 30',
            'dropdownitem30url' => 'Menu Item Link 30',
            'dropdownitem30opennew' => 'Open link in a new window/tab 30',

            'dropdownitem31title' => 'Menu Item Text 31',
            'dropdownitem31url' => 'Menu Item Link 31',
            'dropdownitem31opennew' => 'Open link in a new window/tab 31',

            'dropdownitem32title' => 'Menu Item Text 32',
            'dropdownitem32url' => 'Menu Item Link 32',
            'dropdownitem32opennew' => 'Open link in a new window/tab 32',

            'dropdownitem33title' => 'Menu Item Text 33',
            'dropdownitem33url' => 'Menu Item Link 33',
            'dropdownitem33opennew' => 'Open link in a new window/tab 33',

            'dropdownitem34title' => 'Menu Item Text 34',
            'dropdownitem34url' => 'Menu Item Link 34',
            'dropdownitem34opennew' => 'Open link in a new window/tab 34',

            'dropdownitem35title' => 'Menu Item Text 35',
            'dropdownitem35url' => 'Menu Item Link 35',
            'dropdownitem35opennew' => 'Open link in a new window/tab 35',

            'dropdownitem36title' => 'Menu Item Text 36',
            'dropdownitem36url' => 'Menu Item Link 36',
            'dropdownitem36opennew' => 'Open link in a new window/tab 36',

            'dropdownitem37title' => 'Menu Item Text 37',
            'dropdownitem37url' => 'Menu Item Link 37',
            'dropdownitem37opennew' => 'Open link in a new window/tab 37',

            'dropdownitem38title' => 'Menu Item Text 38',
            'dropdownitem38url' => 'Menu Item Link 38',
            'dropdownitem38opennew' => 'Open link in a new window/tab 38',

            'dropdownitem39title' => 'Menu Item Text 39',
            'dropdownitem39url' => 'Menu Item Link 39',
            'dropdownitem39opennew' => 'Open link in a new window/tab 39',

            'dropdownitem40title' => 'Menu Item Text 40',
            'dropdownitem40url' => 'Menu Item Link 40',
            'dropdownitem40opennew' => 'Open link in a new window/tab 40',
        ],
        'socialmedia' => [
            'website' => 'Website URL',
            'twitter' => 'Twitter URL',
            'facebook' => 'Facebook URL',
            'googleplus' => 'Google+ URL',
            'linkedin' => 'LinkedIn URL',
            'youtube' => 'Youtube URL',
            'vimeo' => 'Vimeo URL',
            'instagram' => 'Instagram URL',
            'pinterest' => 'Pinterest URL',
            'flckr' => 'Flickr URL',
            'tumblr' => 'Tumblr URL',
            'slideshare' => 'Slideshare URL',
            'skype' => 'Skype Account',
            'weibo' => 'Weibo Page',
            'rss' => 'RSS Feed URL',
            'social1' => 'Link Name 1',
            'socialicon1' => 'Link Icon 1',
            'social2' => 'Link Name 2',
            'socialicon2' => 'Link Icon 2',
            'social3' => 'Link Name 3',
            'socialicon3' => 'Link Icon 3',
        ],
        'loginpage' => [
            'loginbgimage' => 'Page background image',
            'useloginbgmask' => 'Enable background image overlay',
        ],
        'advance' => [
            'scsspre' => 'Raw initial SCSS',
            'scss' => 'Raw SCSS',
            'analyticsid' => 'Your Tracking ID',
            'iphoneicon' => 'iPhone Icon',
            'iphoneretinaicon' => 'iPhone Retina Icon',
            'ipadicon' => 'iPad Icon',
            'ipadretinaicon' => 'iPad Retina Icon',
            'hasinternet' => 'Has Internet Connection',

        ],

    ];

    private $checkbox_settings = ['useheadersocial', 'useheroslideshow', 'usealert']; // List of checkbox settings

    private $color_picker_settings = ['brandcolorprimary', 'brandcolorsecondary'];
    private $file_upload_settings = ['logo', 'backgroundimage'];
    private $html_input_settings = ['customhtml', 'footerhtml']; // Settings that allow HTML input
    private $dropdown_settings = [
        'preset' => [
            'default' => 'Default Theme',
            'darkmode' => 'Dark Mode',
            'lightmode' => 'Light Mode',
        ],
        'pagefont' => [
            'arial' => 'Arial',
            'verdana' => 'Verdana',
            'helvetica' => 'Helvetica',
        ],
    ];
    public function get_settings($tab)
    {
        return isset($this->settings[$tab]) ? $this->settings[$tab] : [];
    }
    public function is_file_upload($name)
    {
        return in_array($name, $this->file_upload_settings);
    }
    public function is_dropdown($name)
    {
        return array_key_exists($name, $this->dropdown_settings);
    }

    public function is_html_input($name)
    {
        return in_array($name, $this->html_input_settings);
    }
    public function get_dropdown_options($name)
    {
        return $this->dropdown_settings[$name] ?? [];
    }
    public function is_checkbox($name)
    {
        return in_array($name, $this->checkbox_settings);
    }
    public function is_color_picker($name)
    {
        return in_array($name, $this->color_picker_settings);
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && confirm_sesskey())  {

        foreach ($settingsManager->get_settings($currenttab) as $name => $label) {
            if ($settingsManager->is_checkbox($name)) {
                $value = optional_param($name, null, PARAM_BOOL) ? 1 : 0;
                $settingsManager->save($currenttab, $name, $value);
            } elseif ($settingsManager->is_file_upload($name)) {
                // Step 1: Check if a file was uploaded
                if (isset($_FILES[$name]) && $_FILES[$name]['error'] === UPLOAD_ERR_OK) {
                    // Step 2: Define the context, component, and file area for permanent storage
                    $context = context_system::instance(); // Use system context (or another appropriate context)
                    $component = 'theme_maker'; // Replace with your theme or plugin name
                    $filearea = 'logo'; // File area name (e.g., 'logo', 'image')
                    $itemid = 0; // Item ID (e.g., 0 for general use, or a specific ID like a post ID)
            
                    // Step 3: Validate file size and type
                    $maxbytes = 1048576; // 1MB
                    $acceptedtypes = ['.ico', '.png', '.jpg', 'jpeg']; // Accepted file types
            
                    if ($_FILES[$name]['size'] > $maxbytes) {
                        throw new moodle_exception('filetoobig', 'theme_yourtheme');
                    }
            
                    $fileext = strtolower(pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION));
                    if (!in_array('.' . $fileext, $acceptedtypes)) {
                        throw new moodle_exception('invalidfiletype', 'theme_yourtheme');
                    }
            
                    // Step 4: Get Moodle's file storage API
                    $fs = get_file_storage();
            
                    // Step 5: Delete existing files in the area (if any)
                    $existingfiles = $fs->get_area_files($context->id, $component, $filearea, $itemid, 'id', false);
                    if ($existingfiles) {
                        foreach ($existingfiles as $existingfile) {
                            $existingfile->delete();
                        }
                    }
            
                    // Step 6: Prepare the file record
                    $fileinfo = [
                        'contextid' => $context->id,
                        'component' => $component,
                        'filearea'  => $filearea,
                        'itemid'    => $itemid,
                        'filepath'  => '/', // File path within the file area
                        'filename'  => clean_param($_FILES[$name]['name'], PARAM_FILE), // Sanitize the file name
                    ];
            
                    // Step 7: Save the file
                    $file = $fs->create_file_from_pathname($fileinfo, $_FILES[$name]['tmp_name']);
            
                    if ($file) {
                        // Step 8: Save the file path in settings
                        $filepath = $file->get_filepath() . $file->get_filename();
                        $settingsManager->save($currenttab, $name, $filepath);
                    } else {
                        // Handle the case where the file could not be saved
                        throw new moodle_exception('nofilesaved', 'theme_yourtheme');
                    }
                } else {
                    // Handle the case where no file was uploaded or there was an upload error
                    throw new moodle_exception('nouploadedfile', 'theme_yourtheme');
                }
            } else {
                $value = optional_param($name, '', PARAM_TEXT);
                $settingsManager->save($currenttab, $name, $value);
            }
        }

    \core\notification::add(get_string('contentsaved', 'local_theme_editor'), \core\output\notification::NOTIFY_SUCCESS);
    theme_reset_all_caches();
    \core\notification::add(get_string('cachepurged', 'local_theme_editor'), \core\output\notification::NOTIFY_INFO);
}
echo '<form method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';

foreach ($settingsManager->get_settings($currenttab) as $name => $label) {
    $value = $settingsManager->get($currenttab, $name);

    if ($settingsManager->is_checkbox($name)) {
        $checked = $value ? 'checked' : '';
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="1" ' . $checked . '><br><br>';
    } elseif ($settingsManager->is_color_picker($name)) {
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<input type="color" name="' . $name . '" id="' . $name . '" value="' . s($value) . '"><br><br>';
    } elseif ($settingsManager->is_file_upload($name)) {
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<input type="file" name="' . $name . '" id="' . $name . '">';
        echo '<input type="hidden" name="' . $name . '_draft" value="' . $draftitemid . '"><br><br>';
    } elseif ($settingsManager->is_dropdown($name)) {
        // Render dropdown for predefined options
        $options = $settingsManager->get_dropdown_options($name);
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<select name="' . $name . '" id="' . $name . '">';
        foreach ($options as $optionValue => $optionLabel) {
            $selected = ($value === $optionValue) ? 'selected' : '';
            echo '<option value="' . $optionValue . '" ' . $selected . '>' . $optionLabel . '</option>';
        }
        echo '</select><br><br>';
    } elseif ($settingsManager->is_html_input($name)) {
        // Render HTML input (textarea) for fields that accept HTML content
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<textarea name="' . $name . '" id="' . $name . '" rows="5" cols="50">' . s($value) . '</textarea><br><br>';
    } else {
        // Render regular text input for other settings
        echo '<label for="' . $name . '">' . $label . '</label>';
        echo '<input type="text" name="' . $name . '" id="' . $name . '" value="' . s($value) . '"><br><br>';
    }
}

echo '<button type="submit">' . get_string('savechanges') . '</button>';
echo '</form>';

echo $OUTPUT->footer();