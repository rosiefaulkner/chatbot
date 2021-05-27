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
     * Constructor
     */
    function __construct(array $config = [])
    {
        $this->init($config);
    }

    /**
     * Enqueue scripts
     * 
     * @return void
     */
    public function enqueueScripts() : void
    {
        wp_enqueue_script('chat-window-js', plugin_dir_url(__DIR__) . '/assets/js/script.js', [], '1.0.0', true);
        wp_enqueue_script('chat-widget-js', '//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js', [], '1.0.0', true);

    }

    /**
     * Localize scripts
     * 
     * @return void
     */
    public function localizeScripts() : void
    {
        if (!session_id()) {
            session_start();
        }
        wp_localize_script('chat-window-js', 'vars', ['userId' => sha1(session_id())]);
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
            $settings = get_option('botman_settings');
            $settings['cache_engine'] = $settings['cache_engine'] ?? 'file';

            if ($settings['cache_engine'] == 'file') {
                $cacheDir = $settings['cache_file']['directory'];            
                if (!is_dir($cacheDir)) {
                    mkdir($cacheDir, 0644);
                }
                $this->cache = new DoctrineCache(new PhpFileCache($cacheDir));
            } elseif ($settings['cache_engine'] == 'redis') {
                $host = $settings['cache_redis']['host'];
                $port = $settings['cache_redis']['port'];
                $auth = $settings['cache_redis']['auth'] ?? null;
                $this->cache = new RedisCache($host, $port,  $auth);
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
            $this->bot->hears('Hi', function (BotMan $bot) {
                $bot->startConversation(new OnboardingConversation());
            });
            
            $this->bot->listen();
            exit;
        }
        return $template;
    }

    /**
     * Run application
     * This is the default method executed by BotMan
     */
    public function run()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('wp_footer', [$this, 'localizeScripts'], 0);
        add_filter('template_include', [$this, 'renderChatBot'], 10, 1);
        add_filter('template_include', [$this, 'listenChatBot'], 10, 1);
        add_filter('query_vars', [$this, 'queryVars']);
    }
}
