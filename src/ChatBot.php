<?php

namespace PetPro\Chatbot;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Web\WebDriver;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\DoctrineCache;
use BotMan\BotMan\Drivers\DriverManager;
use PetPro\Chatbot\Conversation\OnboardingConversation;
use Doctrine\Common\Cache\PhpFileCache;

class ChatBot
{
    /**
     * @var \BotMan\BotMan\BotMan
     */
    private $bot;

    /**
     * @var \BotMan\BotMan\Interfaces\CacheInterface
     */
    private $cache;

    /**
     * Constructor
     */
    function __construct(array $config = [])
    {
        $this->init($config);

        add_filter('template_include', [$this, 'renderChatBot'], 10, 1);
        add_filter('template_include', [$this, 'listenChatBot'], 10, 1);
        add_filter('query_vars', [$this, 'queryVars']);
    }

    /**
     * Initialize bot
     * 
     * @param $config Array
     * @return void
     */
    private function init(array $config = []) : void
    {
        DriverManager::loadDriver(WebDriver::class);
        $this->bot = BotManFactory::create($config, $this->getCache());
    }

    /**
     * Get persistent cache
     * 
     * @return \BotMan\BotMan\Interfaces\CacheInterface;
     */
    private function getCache()
    {
        if (!$this->cache) {
            $cacheDir = WP_CONTENT_DIR . '/filecache';

            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0644);
            }

            $this->cache = new DoctrineCache(new PhpFileCache($cacheDir));
        }

        return $this->cache;
    }

    function queryVars($qvars)
    {
        $qvars[] = 'chatbot';
        $qvars[] = 'chatlisten';
        return $qvars;
    }

    public function renderChatBot(string $template): string
    {
        if (get_query_var('chatbot')) {
            return plugin_dir_path(__DIR__) . '/views/chatbot.php';
        }
        return $template;
    }

    public function listenChatBot($template): string
    {
        if (get_query_var('chatlisten')) {
            // Give the bot something to listen for.
            // $this->bot->hears('.*', function (BotMan $bot) {
            //     $bot->typesAndWaits(2);
            //     $bot->reply('Please tell me a few words about what you are trying to find');
            // });
            //             $this->bot->fallback(function ($bot) {
            //                 $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
            //             });

            // Start conversation
            $this->bot->hears('.*(Hi|Hello).*', function (BotMan $bot) {
                $bot->startConversation(new OnboardingConversation());
            });
            // Start listening
            $this->bot->listen();
            exit;
            //return plugin_dir_path(__FILE__) . '/views/chatlisten.php';
        }
        return $template;
    }

    public function run()
    {
        wp_enqueue_script('chat-window-js', plugin_dir_url(__DIR__) . '/assets/js/script.js', [], '1.0.0', true);
        wp_enqueue_script('chat-widget-js', '//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js', [], '1.0.0', true);
    }
}