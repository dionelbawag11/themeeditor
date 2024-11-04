<?php
// db/access.php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/themeeditor:edit' => [
        'riskbitmask' => RISK_CONFIG,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW,
        ],
    ],
];
