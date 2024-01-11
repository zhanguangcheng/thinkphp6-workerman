<?php

use Linkerman\Linkerman;
use Workerman\Protocols\Http\Session;
use Workerman\Protocols\Http\Session\RedisSessionHandler;
use Workerman\Worker;

require_once __DIR__ . '/vendor/autoload.php';

Linkerman::init();

Worker::$pidFile = __DIR__ . '/runtime/workerman.pid';
Worker::$logFile = __DIR__ . '/runtime/workerman.log';

global $worker;
$worker = new Worker('http://0.0.0.0:8080');
$worker->count = 12;
$worker->name = 'thinkphp6-workerman';
$worker->onWorkerStart = [App::class, 'init'];
$worker->onMessage = [App::class, 'send'];
$worker->onWorkerStop = [App::class, 'stop'];

// Save session to Redis
/*
$config = [
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'database' => 0,
    'timeout'  => 2,
    // 'auth'     => '',
    'prefix'   => 'sess_'
];
Session::handlerClass(RedisSessionHandler::class, $config);
*/

Worker::runAll();
