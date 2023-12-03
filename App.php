<?php

use think\event\RouteLoaded;
use Workerman\Connection\TcpConnection;
use Workerman\Lib\Timer;
use Workerman\Protocols\Http\Request;

class ThinkHttp extends think\Http
{
    protected function loadMiddleware(): void
    {
        if (is_file($this->app->getBasePath() . 'middleware.php')) {
            // Change include to include_once
            $file = include_once $this->app->getBasePath() . 'middleware.php';
            if ($file !== true) {
                $this->app->middleware->import($file);
            }
        }
    }

    protected function loadRoutes(): void
    {
        $routePath = $this->getRoutePath();

        if (is_dir($routePath)) {
            $files = glob($routePath . '*.php');
            foreach ($files as $file) {
                // Change include to include_once
                include_once $file;
            }
        }

        $this->app->event->trigger(RouteLoaded::class);
    }
}

class ThinkRequest extends think\Request
{
    public function __construct()
    {
        parent::__construct();
        // Set the raw request body
        $this->input = request_raw_body();
    }
}

class ThinkApp extends think\App
{
    protected $bind = [
        'app' => think\App::class,
        'cache' => think\Cache::class,
        'config' => think\Config::class,
        'console' => think\Console::class,
        'cookie' => think\Cookie::class,
        'db' => think\Db::class,
        'env' => think\Env::class,
        'event' => think\Event::class,
        'http' => ThinkHttp::class,// Change think\Http to ThinkHttp
        'lang' => think\Lang::class,
        'log' => think\Log::class,
        'middleware' => think\Middleware::class,
        'request' => ThinkRequest::class,// Change think\Request to ThinkRequest
        'response' => think\Response::class,
        'route' => think\Route::class,
        'session' => think\Session::class,
        'validate' => think\Validate::class,
        'view' => think\View::class,
        'think\DbManager' => think\Db::class,
        'think\LogManager' => think\Log::class,
        'think\CacheManager' => think\Cache::class,
        'Psr\Log\LoggerInterface' => think\Log::class,
    ];
}

class App
{
    public static ThinkApp $app;
    public static string $headerDate;
    public static int $requestTime;
    public static float $requestTimeFloat;

    public static function init(): void
    {
        self::timer();
        Timer::add(1, [self::class, 'timer']);
        static::$app = new ThinkApp();
    }

    public static function timer(): void
    {
        self::$headerDate = 'Date: ' . gmdate('D, d M Y H:i:s') . ' GMT';
        self::$requestTime = time();
        self::$requestTimeFloat = microtime(true);
    }

    public static function send(TcpConnection $connection, Request $request): void
    {
        // $_SERVER['HTTPS'] = 'on';
        $_SERVER['REQUEST_TIME_FLOAT'] = self::$requestTimeFloat;
        $_SERVER['REQUEST_TIME'] = self::$requestTime;
        ob_start();

        $http = static::$app->http;
        $response = $http->run();
        $response->send();
        $http->end($response);

        header(self::$headerDate);
        $connection->send((string)ob_get_clean());
    }

    public static function stop(): void
    {
        Timer::delAll();
    }
}
