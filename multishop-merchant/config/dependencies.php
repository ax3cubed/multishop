<?php
//Set aliases and import module dependencies
$depends = [
    'base'=> [
    //----------------------
    // Alias mapping
    //----------------------
        'common' => 'multishop-kernel', //actual folder name
        'merchant' => 'multishop-merchant',
        'api' => 'multishop-api',
    ],
    //---------------------------
    // Common modules / resources
    //---------------------------
    'module'=> [
        'common' => [
            'import'=> [
                'components.*',
                'components.behaviors.*',
                'controllers.*',
                'models.*',
                'extensions.*',
                'widgets.SWidget',
                'widgets.susermenu.SUserMenu',
                'widgets.stooltip.SToolTip',
                'widgets.SButtonColumn',
                'services.WorkflowManager',
                'services.workflow.behaviors.*',
                'modules.plans.models.Feature',
                'modules.orders.models.ShippingOrder',
                'modules.activities.models.Activity',
                'modules.activities.behaviors.*',
                'modules.shops.components.*',
                'modules.shops.models.*',
                'modules.shops.behaviors.*',
                'modules.themes.models.*',
                'modules.tags.models.*',
                'modules.products.models.*',
                'modules.payments.models.*',
                'modules.questions.models.*',
                'modules.questions.behaviors.*',
                'modules.plans.models.*',
                'modules.billings.models.*',
            ],
        ],
        'rights' => [
            'import'=> [
                'components.*',
            ],
        ],
        'accounts' => [
            'import'=> [
                'components.*',
                'users.Role',
                'users.Task',
                'users.WebUser',
                'models.LoginForm',
            ],
            'config'=> [
                'apiLoginRoute'=>'oauth2/merchant/login',
                'apiActivateRoute'=>'oauth2/merchant/activate',
                'useReturnUrl'=>true,
                'afterLoginRoute'=>'/welcome', 
                'afterLogoutRoute'=>'/', 
                'welcomeModel'=>'ShippingOrder', 
                'welcomeView'=>'index', 
                'welcomeControllerBehavior'=>'merchant.components.behaviors.MerchantWelcomeControllerBehavior',
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
        'tasks'=> [
            'import'=> [
                'models.*',
                'behaviors.WorkflowBehavior',
            ],
            'config'=> [
                'entryController'=>'merchant',
            ],            
        ],
        'messages'=> [
            'import'=> [
                'models.Message',
            ],
        ],
        'comments' => [
            'import'=> [
                'models.CommentForm',
                'models.Comment',
            ],
        ],
        'likes' => [
            'import'=> [
                'models.LikeForm',
                'models.Like',
            ],
        ],
        'help'=> [
            'config'=> [
                'entryController'=>'merchant',
            ],            
        ],
        'notifications'=> [
            'config'=> [
                'entryController'=>'subscription',
            ],            
        ],
        'analytics' => [
            'import'=> [
                'models.*',
                'components.ChartFactory',
            ],
            'config'=> [
                'dashboardControllerBehavior'=>'application.components.behaviors.MerchantDashboardControllerBehavior',
            ],
        ],
        'search' => [
            'import'=> [
                'behaviors.SearchableBehavior',
            ],
        ],
        'tutorials' => [
            'import'=> [
                'models.Tutorial',
                'models.TutorialSeries',
            ],
            'config'=> [
                'entryController'=>'management',
            ],
        ],
        'tickets' => [
            'import'=> [
                'models.Ticket',
            ],
            'config'=>[
                'enableShopField'=>true,
            ],
        ],
        'media' => [
            'import'=> [
                'models.Media',
                'models.MediaAssociation',
                'models.SessionMedia',
            ],
        ],
        'customers' => [
            'import'=>[
                'models.Customer',
            ],
        ],
        'pages' => [
            'import'=>[
                'models.Page',
                'models.PageLayout',
            ],
        ],
    ],
    //----------------------
    // Local modules
    //----------------------
    'local'=> [
        'activities'=> [
            'config'=> [
                'entryController'=>'merchant',
            ],            
        ],
        'shops' => [
            'config'=> [
                'entryController'=>'management',
            ],            
        ],
        'attributes' => [],//@todo Currently not utilize
        'payments'=> [
            'config'=> [
                'entryController'=>'management',
            ],
        ],
        'orders' => [
            'config'=> [
                'entryController'=>'merchant',
            ],
        ],
        'items' => [
            'config'=> [
                'entryController'=>'merchant',
            ],
        ],
        'shippings' => [
            'import'=> [
                'models.ShippingTierForm',
            ],            
        ],
        'products' => [
            'import'=> [
                'models.ProductShippingForm',
                'models.CategoryForm',
                'models.CategorySubForm',
            ],            
        ],
        'brands' => [],
        'campaigns' => [
            'import'=> [
                'models.CampaignShippingForm',
            ],            
        ],
        'inventories' => [],
        'taxes' => [],
        'news'=> [
            'import'=> [
                'models.NewsForm',
            ],
            'config'=> [
                'entryController'=>'management',
            ],
        ],        
        'themes' => [
            'config'=> [
                 'entryController'=>'portal',
            ],
        ],
        'questions'=> [
            'config'=> [
                'entryController'=>'merchant',
                'taskAskUrl'=>'/tasks/question/ask', 
                'taskAnswerUrl'=>'/tasks/question/answer', 
            ],            
        ],
        'community'=>[],
        'plans' => [
            'config'=> [
                 'entryController'=>'subscription',
            ],
        ],   
        'billings' => [
            'config'=> [
                'paymentGateway'=>'common.modules.payments.plugins.braintreeRecurringBilling.components.BraintreeRecurringBillingGateway',
            ],            
        ],
        'wcm' => [],//this is for simple web content management module for merchant app
        'chatbots' => [],//support chatbot settings
    ],
];

// The app directory path, e.g. /path/to/multishop-app
$appPath = dirname(dirname(__FILE__));

loadDependencies(ROOT,$depends, $appPath);

return $depends;