<?php

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