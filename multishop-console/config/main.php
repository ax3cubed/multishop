<?php
/**
 * MAIN CONFIG
 */
date_default_timezone_set('Asia/Singapore');
$basepath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
$appName = basename(dirname(dirname(__FILE__)));// The app directory name, e.g. multishop-app

//////////////////////////////////////
//Load global config params
/////////////////////////////////////
$params = require(KERNEL.'/config/params.php');
//////////////////////////////////////
//Load local config params
/////////////////////////////////////
$params = array_merge(require($basepath.DIRECTORY_SEPARATOR.'config/params.php'),$params);
Yii::trace('params >> '.var_export($params,true));
//////////////////////////////////////
//Load dependencies (set aliases and import modules)
/////////////////////////////////////
$dependencies = require_once($basepath.DIRECTORY_SEPARATOR.'config/dependencies.php');
//Yii::setPathOfAlias('common',$basepath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'common');
//Yii::import('common.components.*');
//Yii::import('common.models.*');

// This is the main Web application configuration. Any writable
// CConsoleApplication properties can be configured here.
return [
    'id'=>'console',//mainly used in Sii.php
    'basePath'=> $basepath,
    'commandPath'=> $basepath.'/commands',
    'name'=>'Console App',
    'language'=>'en_sg',

    //////////////////////////////////////
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    //////////////////////////////////////
    'params'=>$params,    

    // preloading 'log' component
    'preload'=> ['log'],

    // autoloading model and component classes
    'import'=> [
        'common.modules.tasks.models.Process',
    ],

    //load module
    'modules'=>loadModules($dependencies),

    'commandMap'=> [
        'migrate'=>[
            'class'=>'system.cli.commands.MigrateCommand',
            'migrationPath'=>'application.migrations',
            'migrationTable'=>'s_migration',
            'connectionID'=>'db',
            // 'templateFile'=>'application.migrations.template',
        ],
    ],
    
    // application components
    'components'=>[

        'serviceManager'=>[ 
            'class' => 'common.services.ServiceManager',
        ],

        'request' => [
            'class' => 'common.components.SHttpRequest',
        ],    
        
        'urlManager'=>[ 
            'class'=>'SUrlManager',
            'hostDomain'=>$params['HOST_DOMAIN'],
            'merchantDomain'=>$params['MERCHANT_DOMAIN'],
            'shopDomain'=>$params['SHOP_DOMAIN'],
            //'cdnDomain'=>'',//if not set, follow host domain
            'forceSecure'=>true,
            'urlFormat'=>'path',
        ],
        
        'image'=> [
            'class'=> 'SImgManager',
            'modelClass'=> 'MediaAssociation',
            'baseRelativePath' => $appName.'/runtime',//set to dummy location for now - unlikely to use
            'versions'=> loadImageVersions(),
        ],
        
        'db'=> require(KERNEL.'/config/datasource.php'),

        'commonCache'=>[
            'class'=>'common.components.SCache',
            'remoteDelete'=>false,
        ],
        
        'cache'=>[
            'class'=>'system.caching.CFileCache',//use file cache instead as CDbCache is not working in some environment
        ],

        'log'=> [
            //'class'=>'CLogRouter',
            'class'=>'SLogRouter',
            'autoFlush' => 2,
            'routes'=> [
                [
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                    //'categories'=>'',
                ],
                [
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace, info',
                    'categories'=>'application',
                   // 'categories'=>'',
                ],
                // uncomment the following to show log messages on web pages
                /*
                array(
                        'class'=>'CWebLogRoute',
                ),
                */
            ],
        ],
    ],
];
