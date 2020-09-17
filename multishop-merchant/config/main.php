<?php
logHttpHeader();
$basepath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
$appName = basename(dirname(dirname(__FILE__)));// The app directory name, e.g. multishop-app
$webapp = new SWebApp($appName,$basepath);
//$webapp->enableSystemTrace = true;
$webapp->import([
    'application.models.*',
    'application.components.*',
]);
$webapp->setCommonComponent('ctrlManager',['class'=> 'MerchantControllerManager']);
$webapp->addComponents([
    'themeManager'=>[
        'basePath'=> $basepath.'/modules/themes/resources/site'  
    ],
    'user'=>[
        'class'=>'MerchantUser',
        //enable cookie-based authentication
        'allowAutoLogin'=>true,
        'loginUrl'=>['/signin'],
    ],
   'request' => [
        'class' => 'common.components.SHttpRequest',
        'enableCsrfValidation' => true,
        'enableCookieValidation'=>true,
        'csrfTokenName'=>$webapp->params['CSRF_TOKEN_NAME'],
        'csrfSkippedRoutes'=> [
            'billings/braintree/webhook',
            'site/locale',
        ],       
    ],    
    'session' => [
        'class' => 'SHttpSession',
    ],
    'urlManager'=> [
        'class'=>'MerchantUrlManager',
        'hostDomain'=>$webapp->params['HOST_DOMAIN'],
        'shopDomain'=>$webapp->params['SHOP_DOMAIN'],
        //'cdnDomain'=>'',//if not set, follow host domain
        'forceSecure'=>false,
        'homeUrl'=>'/',
        'urlFormat'=>'path',
        'showScriptName'=>false,
        'rules'=> [
            //More at MerchantUrlManager
            //custom rules go first
            'signin'=>'accounts/authenticate/login',
            'signup'=>'register/index',
            'welcome'=>'accounts/welcome/index',
            'message/view/*'=>'messages/management/view',
            'message/compose/*'=>'messages/management/compose',
            'message/reply/*'=>'messages/management/reply',
            'messages/sent'=>'messages/management/sent',                
            'messages/unread'=>'messages/management/unread',                
            'order/view/*'=>'orders/merchant/view',
            'shipping-orders'=>'orders/merchant/index',
            'purchase-orders'=>'orders/merchantPO/index',
            'item/view/*'=>'items/merchant/view',
            'shop'=>'shops/management/index',
            'shop/start'=>'shops/management/start',
            'shop/view/*'=>'shops/management/view',
            'shop/<controller>/<action:\w+>'=>'shops/<controller>/<action>',
            'shop/themes/update/*'=>'shops/design/update',
            'shop/themes/*'=>'shops/design/index',
            'shop/design/update/*'=>'shops/design/update',
            'shop/design/*'=>'shops/design/index',
            'shop/<controller>/*'=>'shops/<controller>',
            'goto/<shop>/<view:[a-zA-Z0-9-]+>'=>'shops/management/goto',
            'goto/<shop>/<view:[a-zA-Z0-9-]+>/<subview:[a-zA-Z0-9-]+>'=>'shops/management/goto',
            'news/view/*'=>'news/management/view',
            'comment/view/*'=>'comments/management/view',
            'question/view/*'=>'questions/merchant/view',
            'question/ask'=>'questions/merchant/ask',
            'shop-questions'=>'questions/management/index',
            'shop-settings'=>'shops/settings/index',
            'shop-themes'=>'shops/design/index',
            'shop-design'=>'shops/design/index',
            'paymentMethods'=>'payments/management/index',
            'paymentMethod/view/*'=>'payments/management/view',
            'attribute/view/*'=>'attributes/management/view',
            'brand/view/*'=>'brands/management/view',
            'campaign/bga/view/*'=>'campaigns/bga/view',
            'campaign/sale/view/*'=>'campaigns/sale/view',
            'campaign/promocode/view/*'=>'campaigns/promocode/view',
            'shipping/view/*'=>'shippings/management/view',
            'shipping/zone/view/*'=>'shippings/zone/view',
            'tax/view/*'=>'taxes/management/view',
            'customer/view/*'=>'customers/management/view',
            'tutorial/view/*'=>'tutorials/management/view',
            'ticket/view/*'=>'tickets/management/view',
            'media/assets/preview/*'=>'media/preview',
            'media/assets/*'=>'media/management/assets',
            'media/view/*'=>'media/management/view',
            'media/download/*'=>'media/management/download',
            'file/download/*'=>'media/download/attachment/*',
            'categories'=>'products/category/index',
            'page/view/*'=>'pages/management/view',
            'product/view/*'=>'products/management/view',
            'product/inventory/view/*'=>'inventories/management/view',
            'product/<controller>/<action:\w+>'=>'products/<controller>/<action>',
            'product/<controller>/<action:\w+>/*'=>'products/<controller>/<action>/*',
            'product/<controller>/*'=>'products/<controller>',
            'tasks/campaign/bga/<action:\w+>'=>'tasks/campaignBga/<action>',//case sensitive
            'tasks/campaign/sale/<action:\w+>'=>'tasks/campaignSale/<action>',//case sensitive
            'tasks/campaign/promocode/<action:\w+>'=>'tasks/campaignPromocode/<action>',//case sensitive
            'tasks/tutorialseries/<action:\w+>'=>'tasks/tutorialSeries/<action>',//case sensitive
            'dashboard'=>'analytics/management/index',
            'billing'=>'billings',
            'receipt/view/*'=>'billings/receipt/view',
            'receipt/download/*'=>'billings/receipt/download',
            'billing/payment'=>'billings/payment',
            'billing/payment/<action:\w+>'=>'billings/payment/<action>',
            'billing/settings'=>'billings/settings',
            'subscriptions'=>'billings/subscriptions',
            'subscription/<action:\w+>/*'=>'billings/subscription/<action>',
            'themes/preview/*'=>'themes/preview/index',
            'themes/buy/*'=>'themes/buy/index',
            'themes/<theme_name>'=>'themes/portal/view',
            'pages/layout/edit/<page_name>' => 'pages/layout/index',
            // default to site/url to handle url (below function still exists and valid!)
            //'<controller:[a-zA-Z0-9-]+>/<action:[a-zA-Z0-9-]+>'=>'site/url',
            //'<controller:[a-zA-Z0-9-]+>/*'=>'site/url',
            // default static site page
            //'<view:[a-zA-Z0-9-]+>/'=>'site/page',//for static page like about
        ],
    ],
    'drift' => [
        'class' =>'common.extensions.drift.Drift',
        'id'=> $webapp->parseString('DRIFT_ID'),
        'app'=> $webapp->id,
        'enable'=> $webapp->parseArray('DRIFT_ENABLE'),
        'enableAfterLogin'=> $webapp->parseArray('DRIFT_AFTER_LOGIN'),
    ],
    'googleAnalytics' => [
        'class' =>'common.extensions.googleAnalytics.GoogleAnalytics',
        'enable'=> $webapp->parseBoolean('GOOGLE_ANALYTICS'),
        'gtmAccount' => $webapp->params['GTM_ACCOUNT'],
    ],
    'filter'=> [
        'class'=>'SFilter',
        'rules'=>['flash','subscription'],
    ],
]);
return $webapp->toArray();