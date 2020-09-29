<?php
/**
 * Yii command line script file.
 *
 * This script is meant to be run on command line to execute
 * one of the pre-defined console commands.
 *
 */
// fix for fcgi
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

require_once(dirname(__FILE__).'/../common/config/globals.php');
//load customized Yii class (enable both Yii 1.x and Yii 2.x)
require(dirname(__FILE__).'/../common/components/Yii.php');
$config=dirname(__FILE__).'/config/main.php';

if(isset($config)) {
    $app=Yii::createConsoleApplication($config);
    $app->commandRunner->addCommands(YII_PATH.'/cli/commands');
}
else
    $app=Yii::createConsoleApplication(array('basePath'=>dirname(__FILE__).'/cli'));

$env=@getenv('YII_CONSOLE_COMMANDS');
if(!empty($env))
	$app->commandRunner->addCommands($env);

$app->run();