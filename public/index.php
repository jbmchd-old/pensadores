<?php

 /**
  * Display all errors when APPLICATION_ENV is development.
  */

mb_internal_encoding('UTF-8');
ini_set('upload_max_filesize', '30720000');

 if($_SERVER['HTTP_HOST']=='ospensadores.com'){
    ini_set("display_errors", 0);
    ini_set("log_errors", 1);
 } else {
    error_reporting(E_ALL);
    ini_set("display_errors",2);
    ini_set('error_reporting', E_ALL | E_STRICT);
 }

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
