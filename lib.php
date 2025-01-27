<?php
defined('MOODLE_INTERNAL') || die();

function local_theme_editor_get_theme_settings() {
    return get_config('theme');
}

function local_theme_editor_save_theme_settings($settings) {
    set_config('theme', $settings);
}

function local_theme_editor_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    $itemid = array_shift($args); // Get itemid
    $filename = array_pop($args); // Get the original filename
    $filepath = '/' . implode('/', $args) . '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_theme_editor', $filearea, $itemid, $filepath, $filename);

    if (!$file) {
        send_file_not_found();
    }

    // Serve the file with original filename
    send_stored_file($file, 0, 0, $forcedownload, $options);
}