<?php

namespace PetPro\ChatBot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\DoctrineCache;
use BotMan\BotMan\Cache\RedisCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Interfaces\CacheInterface;
use BotMan\Drivers\Web\WebDriver;
use Doctrine\Common\Cache\PhpFileCache;
use PetPro\ChatBot\Conversation\OnboardingConversation;

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
     * @var string 'file' or 'redis'
     */
    private $cacheType = 'file';

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
     * @throws \Exception
     */
    private function getCache() : CacheInterface
    {
        if (!$this->cache) {
            if ($this->cacheType == 'redis') {
                $this->cache = new RedisCache('127.0.0.1', 6379);
            } elseif ($this->cacheType == 'file') {
                $cacheDir = WP_CONTENT_DIR . '/filecache';                
                if (!is_dir($cacheDir)) {
                    mkdir($cacheDir, 0644);
                }
                $this->cache = new DoctrineCache(new PhpFileCache($cacheDir));
            } else {
                throw new \Exception('Unknown cache type specified');
            }
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
            $this->bot->hears('(.*)', function (BotMan $bot, string $name) {
                $conversation = new OnboardingConversation();
                $conversation->setName($name);
                $bot->startConversation($conversation);
            });
            
            $this->bot->listen();
            exit;
        }
        return $template;
    }

    public function run()
    {
        wp_enqueue_script('chat-window-js', plugin_dir_url(__DIR__) . '/assets/js/script.js', [], '1.0.0', true);
        wp_enqueue_script('chat-widget-js', '//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js', [], '1.0.0', true);
    }
}