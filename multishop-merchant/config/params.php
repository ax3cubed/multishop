<?php 
return [
    /**
     * configuraion for local information 
     */
    'ORG_NAME' => readConfig('app','organization'),
    'SITE_NAME' => readConfig('app','name'),
    'SITE_LOGO' => false, //indicate if to use a brand image as site logo; if false, SITE_NAME will be used
    /**
     * configuration for domain
     */
    'HOST_DOMAIN' => readConfig('domain','host'),    
    'API_DOMAIN' => readConfig('domain','api'),  
    'BOT_DOMAIN' => readConfig('domain','bot'),  
    'SHOP_DOMAIN' => readConfig('domain','shop'),  
    /**
     * configuration for shop resources
     */
    'SHOP_THEME_BASEPATH' => readConfig('system','shopThemePath'),
    'SHOP_WIDGET_BASEPATH' => readConfig('system','shopWidgetPath'),
    /**
     * configuration for OAUTH - hybridoauth login
     */
    'OAUTH' => true,
    /**
     * configuration for billings
     */
    'RECEIPT_TEMPLATE_EMAIL' => 'common.modules.billings.receipts.template_email',//for recurring email receipt 
    'RECEIPT_TEMPLATE_EMAIL_ADHOC' => 'common.modules.billings.receipts.template_email_adhoc',//for one time email receipt 
    'RECEIPT_TEMPLATE_PDF' => 'common.modules.billings.receipts.receipt.template_pdf',//for recurring pdf receipt 
    'RECEIPT_TEMPLATE_PDF_ADHOC' => 'common.modules.billings.receipts.receipt.template_pdf_adhoc',//for one time pdf receipt 
    /**
     * configuration for help wizard
     */
    'WIZARD_APP_ID' => 'merchant',
    /**
     * configuration to enable Google Analytics
     */
    'GTM_ACCOUNT' => readConfig('googleanalytics','gtmAccount'),
    'GOOGLE_ANALYTICS' => readConfig('googleanalytics','enable'),
    /**
     * configuration to enable Drift live chat
     */
    'DRIFT_ID' => readConfig('drift','id'),
    'DRIFT_ENABLE' => readConfig('drift','enable'),
    'DRIFT_AFTER_LOGIN' => readConfig('drift','enableAfterLogin'),
    /**
     * configuraion for facebook app to be used in facebook page (as one of the tabs)
     */
    'FACEBOOK_PAGEAPP_ID' => readConfig('facebook','pageAppId'),
    'FACEBOOK_PAGEAPP_SECRET' => readConfig('facebook','pageAppSecret'),
];


