<?php

namespace PetPro\ChatBot;

class ChatBotSettings
{
    /**
     * Constructor
     */
    function __construct()
    {       
        add_action('admin_init', [$this, 'registerChatbotSettings']);
        add_action('admin_menu', [$this, 'registerChatbotSettingsPage']);
    }
    
    /**
     * Register chatbot settings
     * 
     * @return void
     */
    function registerChatbotSettings() : void
    {
        register_setting('botman_settings', 'botman_settings', [
            'default' => [
                'cache_engine' => 'file',
                'cache_file' => [
                    'directory' => WP_CONTENT_DIR . '/filecache',
                ],
                'cache_redis' => [
                    'host' => '127.0.0.1',
                    'port' => '6379',
                    'auth' => '',
                ],
            ],
        ]);
    }
    
    /**
     * Register a custom menu page
     * 
     * @return void
     */
    function registerChatbotSettingsPage() : void
    {
        add_menu_page(
            __('ChatBot Settings', 'textdomain'),
            'ChatBot',
            'manage_options',
            'chatbot-settings',
            [$this, 'displaySettings'],
            'dashicons-format-chat'
        );
    }

    /**
     * Display the plugin settings page
     * 
     * @return void
     */
    public function displaySettings() : void
    {
        include_once(plugin_dir_path(__DIR__) . '/views/settings.php');
    }
}