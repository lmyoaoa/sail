<?php
/**
cron主入口文件
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,OPTIONS');
header('Access-Control-Allow-Headers: Content-type');
header('Access-Control-Allow-Credentials: true');

//定义项目名，对应配置在/config/appConfig
define('APP_NAME', 'main');
define('IS_CLI', true);

//程序目录
define('APP_PATH', dirname(__FILE__) . '/');
require APP_PATH . 'sail.php';


