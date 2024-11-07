<?php
defined('MOODLE_INTERNAL') || die();

function local_theme_editor_get_theme_settings() {
    return get_config('theme');
}

function local_theme_editor_save_theme_settings($settings) {
    set_config('theme', $settings);
}