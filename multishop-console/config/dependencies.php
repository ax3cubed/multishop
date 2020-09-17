<?php
//Set aliases and import module dependencies
$root = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..';

$depends = [
    'base'=>[
    //----------------------
    // Alias mapping
    //----------------------        
        'common' => 'multishop-kernel', //actual folder name
        'console' => 'multishop-console',
        'schema' => 'multishop-schema',
    ],
    //---------------------------
    // Common modules / resources
    //---------------------------
    'module'=> [
        'common' => [
            'import'=> [
                'components.*',
                'models.*',
            ],
        ],
        'search' => [
            'config'=> [
                'loadAssets'=>false,
            ],
        ],
        'media' => [
            'import'=>[
                'models.*',
            ],
        ],
        'images' => [
            'import'=> [
                'components.*',
                'components.Img',
            ],
            'config'=> [
                'createOnDemand'=>true, // requires apache mod_rewrite enabled
            ],
        ],        
        //plain modules contains components/behaviors/models without controllers/views
        'brands' => [],
        'campaigns' => [],
        'products' => [],
        'shippings' => [],
        'taxes' => [
            'import'=>[
                'behaviors.TaxableBehavior',
            ],
        ],
        'inventories' => [
            'import'=>[
                'models.LowInventoryDataProvider',
            ],
        ],        
    ],
    //----------------------
    // Local modules
    // Format: local module name
    //----------------------
    'local'=>[],
];

// The app directory path, e.g. /path/to/multishop-app
$appPath = dirname(dirname(__FILE__));

loadDependencies(ROOT,$depends, $appPath);

return $depends;
