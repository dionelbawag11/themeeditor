<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/theme_editor:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'teacher' => CAP_ALLOW, // Allow teachers to view
        ],
    ],
    'local/theme_editor:edit' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'teacher' => CAP_ALLOW, // Allow teachers to edit
        ],
    ],
];