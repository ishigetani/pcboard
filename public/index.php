<?php

//Ubench.phpの読み込み
require_once 'Ubench.php';
$bench = new Ubench();

try {

    define('BASE_DIR', dirname(__DIR__));
    define('APP_DIR', BASE_DIR . '/app');

	/**
	 * Read the configuration
	 */
	$config = include APP_DIR ."/config/config.php";

    include APP_DIR. "/config/loader.php";

    include APP_DIR . '/config/service.php';

	/**
	 * Handle the request
	 */
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}

//処理時間の計測終了
$bench->end();

//メモリ使用量(memory_get_usage(true))
var_dump($bench->getMemoryUsage());

//最大値(memory_get_peak_usage(true))
var_dump($bench->getMemoryPeak());