<?php

/**
 * Plugin Name: ChatBot
 * Plugin URI: https://www.rosiefaulkner.com
 * Description: Adds interactive chatbot for directing users
 * Author: Rosie Faulkner
 * Version: 1.0.0
 * Author URI: https://www.rosiefaulkner.com
 */

if (!is_admin()) {
    require_once(__DIR__ . '/vendor/autoload.php');

    $config = [
        'web' => [
            'matchingData' => [
                'driver' => 'web',
            ],
        ]
    ];

    (new \PetPro\ChatBot\ChatBot($config))->run();
}

