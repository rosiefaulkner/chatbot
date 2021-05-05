<?php


/**
 * Plugin Name: ChatBot
 * Plugin URI: https://www.rosiefaulkner.com
 * Description: Adds interactive chatbot for directing users
 * Author: Rosie Faulkner
 * Version: 1.0.0
 * Author URI: https://www.rosiefaulkner.com
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once(__DIR__ . '/vendor/autoload.php');

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Web\WebDriver;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\DoctrineCache;
use BotMan\BotMan\Drivers\DriverManager;
use PetPro\Chatbot\OnboardingConversation;
use Doctrine\Common\Cache\PhpFileCache;

class chatBot
{
    private $botman;
    function __construct(array $config = [])
    {
        // Load the driver(s) you want to use
        DriverManager::loadDriver(WebDriver::class);
        // Instantiates cache driver
        $adapter = new PhpFileCache(
            '/nas/content/live/petprodev1/wp-content/filecache'
        );
        // Create an instance
        $this->botman = BotManFactory::create($config, new DoctrineCache($adapter));
        add_filter('script_loader_src', [$this, 'addIdToScript'], 10, 2);
        add_filter('template_include', [$this, 'renderChatBot'], 10, 1);
        add_filter('template_include', [$this, 'listenChatBot'], 10, 1);
        add_filter('query_vars', [$this, 'queryVars']);
    }
    function queryVars($qvars)
    {
        $qvars[] = 'chatbot';
        $qvars[] = 'chatlisten';
        return $qvars;
    }
    public function renderChatBot($template): string
    {
        $isChatbot = get_query_var('chatbot');
        if ($isChatbot) {
            $this->scripts();
            return plugin_dir_path(__FILE__) . '/views/chatbot.php';
        }
        return $template;
    }
    public function listenChatBot($template): string
    {
        $isChatbot = get_query_var('chatlisten');
        if ($isChatbot) {
            // Give the bot something to listen for.
            $this->getBot()->hears('hi', function (BotMan $bot) {
                $bot->reply('Please tell me a few words about what you are trying to find');
            });
            //             $this->getBot()->fallback(function ($bot) {
            //                 $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
            //             });

            // Start conversation
            $this->getBot()->hears('hi', function (BotMan $bot) {
                $conversation = new OnboardingConversation();
                $conversation->setBot($bot);
                $bot->startConversation($conversation);
            });
            // Start listening
            $this->getBot()->listen();
            exit;
            //return plugin_dir_path(__FILE__) . '/views/chatlisten.php';
        }
        return $template;
    }

    public function getBot()
    {
        return $this->botman;
    }
    public function scripts()
    {
        wp_enqueue_style('chatbot-css', '//cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css');
        wp_enqueue_script('chatbot-js', '//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js', [], '1.0.0', true);
    }

    public function addIdToScript(string $src, string $handle): string
    {
        if ($handle == 'chatbot-js') {
            return $src . "' id='botmanWidget'";
        }
        return $src;
    }

    public function displayChat()
    {
        wp_enqueue_script('chat-window-js', plugin_dir_url(__FILE__) . '/assets/js/script.js', [], '1.0.0', true);
        wp_enqueue_script('chat-widget-js', '//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js', [], '1.0.0', true);
    }
}

$config = [
    'web' => [
        'matchingData' => [
            'driver' => 'web',
        ],
    ]
];
$chatBot = new chatBot($config);

// Display chat
$chatBot->displayChat();

