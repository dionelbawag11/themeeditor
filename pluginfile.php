<?php 
function local_theme_editor_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel != CONTEXT_USER) {
        return false;
    }

    if ($filearea !== 'logo' && $filearea !== 'backgroundimage') {
        return false;
    }

    $fs = get_file_storage();
    $itemid = array_shift($args);
    $filepath = '/' . implode('/', $args);
    $file = $fs->get_file($context->id, 'theme_maker', $filearea, $itemid, $filepath, $filename);

    if (!$file) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}

?>