<?php
return [
    /**
     * configuration for domain
     */
    'HOST_DOMAIN' => readConfig('domain','host'),    
    'MERCHANT_DOMAIN' => readConfig('domain','merchant'),      
    'SHOP_DOMAIN' => readConfig('domain','shop'),      
    'API_DOMAIN' => readConfig('domain','api'),      
    /**
     * configuration for backup path (database, files etc)
     */
    'BACKUP_PATH' => readConfig('system','backupDir'),    
];