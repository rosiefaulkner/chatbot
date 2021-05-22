<?php

/**
 * Plugin Name: ChatBot
 * Plugin URI: https://www.rosiefaulkner.com
 * Description: Adds interactive chatbot for directing users
 * Author: Rosie Faulkner
 * Version: 1.0.0
 * Author URI: https://www.rosiefaulkner.com
 */

require_once(__DIR__ . '/vendor/autoload.php');

if (!is_admin()) {
    $config = [
        'web' => [
            'matchingData' => [
                'driver' => 'web',
            ],
        ]
    ];

    (new \PetPro\ChatBot\ChatBot($config))->run();
} else {
    new \PetPro\ChatBot\ChatBotSettings();
}
