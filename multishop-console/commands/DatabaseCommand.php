<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.services.notification.Dispatcher");
Yii::import("common.services.notification.events.EmailEvent");
Yii::import('console.components.CommandLogFileTrait');
/**
 * Description of DatabaseCommand
 * 
 * @author kwlok
 */
class DatabaseCommand extends SCommand 
{
    use CommandLogFileTrait {
        init as traitInit;
    }     
    protected $backupPath;
    /**
     * Init
     */
    public function init() 
    {
        $this->logFileName = 'database';
        $this->backupPath = param('BACKUP_PATH');
        $this->traitInit();
    }      
    /**
     * Create database schema
     */
    public function actionSchema()
    {
        $migration = new DatabaseInitMigration();
        $migration->run($this);
    }
    /**
     * Backup database using mysqldump
     * <Unix command>: mysqldump --opt -u [uname] -p[pass] [dbname] | gzip -9  > [dbname]_backup.sql.gz
     * <Usage>: php console database backup --db=value --u=value [--p=]
     * 
     * @param type $db database name (optional); If null, it will read from datasource.php
     * @param type $u username (optional); ; If null, it will read from datasource.php
     * @param type $p password (optional); If null, will enter at command prompt (safer), not leaving trails at ~/.bash_history for all to see
     * @param type $email Email address to receive backup file
     * @return int
     */
    public function actionBackup($db=null,$u=null,$p=null,$email=null) 
    {   
        if ($db==null)
            $db = readDBConfig('dbname');
        if ($u==null)
            $u = readDBConfig('username');
        if ($p==null)
            $p = readDBConfig('password');
        
        $this->logInfo(__METHOD__.' start..');
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** new backup *****\n", FILE_APPEND);
        
        try {
            $backup = $db.'_'.date('Ymd').'_backup.sql.gz';
            $backupFile = $this->backupPath.DIRECTORY_SEPARATOR.$backup;
            $backupCommandMasked = 'mysqldump --opt --single-transaction -u ... -p*** '.$db.' | gzip -9 > '.$backupFile;
            $this->logInfo('Execute (masked): '.$backupCommandMasked);
            $backupCommand = str_replace('***', $p, str_replace('...', $u, $backupCommandMasked));
            $this->logInfo(shell_exec($backupCommand));
            file_put_contents($this->logFile, date('m/d/Y h:i:s a')." backup completed: $backupFile\n", FILE_APPEND);
            if (isset($email)){
                Dispatcher::email(new EmailEvent(
                                        $email,
                                        $email,
                                        'Database backup',
                                        'Backup file: '.$backup,
                                        EmailEvent::SYNCHRONOUS,
                                        $backupFile));
                $this->logInfo('Email: '.$email);
                file_put_contents($this->logFile, date('m/d/Y h:i:s a')." backup emailed to: $email\n", FILE_APPEND);
            }
            $this->logInfo('Completed.');
            
            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }      
    /**
     * Restore database (for blank database, target database need to be existent first)
     * 
     * <Unix command>: gzcat [backup_file] | mysql -u [uname] -p[pass] [dbname]
     * <Usage>: php console database restore --backup=value --db=value --u=value [--p=]
     * 
     * @param type $backup Backup file name (in gz format), and stored in BACKUP_PATH
     * @param type $db Target database name
     * @param type $u username
     * @param type $p password (optional); If null, will enter at command prompt (safer), not leaving trails at ~/.bash_history for all to see
     * @param type $os OS env (optional); Default use gunzip command "zcat", for mac os, "gzcat" will be used
     * @return int
     */
    public function actionRestore($backup,$db=null,$u=null,$p=null,$os='linux') 
    {   
        if ($db==null)
            $db = readDBConfig('dbname');
        if ($u==null)
            $u = readDBConfig('username');
        if ($p==null)
            $p = readDBConfig('password');
        
        $this->logInfo(__METHOD__.' start..');
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** new restore *****\n", FILE_APPEND);
        
        try {
            
            $backupFile = $this->backupPath.DIRECTORY_SEPARATOR.$backup;
            $restoreCommandMasked = ($os=='mac'?'gzcat ':'zcat ').$backupFile.' | mysql -u ... -p*** '.$db;
            $this->logInfo('Execute (masked): '.$restoreCommandMasked);
            $restoreCommand = str_replace('***', $p, str_replace('...', $u, $restoreCommandMasked));
            $this->logInfo(shell_exec($restoreCommand));
            file_put_contents($this->logFile, date('m/d/Y h:i:s a')." restore $backupFile to target database $db completed.\n", FILE_APPEND);
            $this->logInfo('Completed.');

            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }      
    /**
     * TODO: Import DB (for existing database with tables)
     * <Unix command>: mysqlimport -u [uname] -p[pass] [dbname] [backupfile.sql]
     */
    public function actionImport()
    {
        //todo
    }
    /**
     * Archive certain tables?
     * e.g. big db table such as s_message_queue, s_activity, s_transition 
     * Need a archive solution (but data cannot be purged)
     */
    public function actionArchive()
    {
        //todo
    }
    /**
     * A simple method to test db connection
     * This test if table s_config is created and able to query upon
     */
    public function actionConn()
    {
        foreach (Config::model()->findAll() as $config) {
            $this->logInfo($config->name.', value='.$config->value);
        }
    }  
}

Yii::import('console.extensions.txsql.TXDbMigration');
/**
 * Description of DatabaseInitMigration
 *
 * @author kwlok
 */
class DatabaseInitMigration extends TXDbMigration 
{    
    protected $command;
    protected $suppressInfo = true;
    /**
     * Run database installation
     */
    public function run($command)
    {
        $runSql = function ($alias,$schema){
            $sqlfie = Yii::getPathOfAlias($alias).DIRECTORY_SEPARATOR.$schema;
            $this->executeFile($sqlfie);
        };
        
        $this->command = $command;
        foreach ($this->schemas as $alias => $schema) {
            if (is_array($schema)){
                foreach ($schema as $value) {
                    $runSql($alias,$value);
                }
            }
            else {
                $runSql($alias,$schema);
            }
        }
        
        $this->command->writeLog("    << command completed >>");
        
    }
    /**
     * OVERRIDE method to write log into console and log file
     * @param type $filePath
     * @param type $next
     */
    protected function printLine($filePath, $next = null) 
    {
        $this->command->writeLog("    > execute file $filePath ..." . $next);
    }    
    /**
     * Get all schema files and run in sequential order
     * NOTE: sequence of schema file loading is IMPORTANT
     * For 'schema' path alias setting, refer to config/dependencies.php
     * 
     * @return array ["alias"=>"schema file"]
     */
    protected function getSchemas()
    {
        return [
            //run all schema files in path alias 'schema'
            //default schema file name follows format: [module_name].sql
            'schema.mysql'=>[
                'migrations.sql',//contains all migrations history
                'rights.sql',//rights module - authentication and authorization tables
                'api.sql',//required to for multishop-api app
                'oauth.sql',//required to support oauth login
                'configs.sql',
                'users.sql',
                'accounts.sql',
                'accounts-data.sql',//initial data required by accounts module
                'activities.sql',
                'media.sql',
                'messages.sql',
                'notifications.sql',
                'shops.sql',
                'carts.sql',
                'orders.sql',
                'items.sql',
                'attributes.sql',
                'products.sql',
                'brands.sql',
                'campaigns.sql',//dependency - has to run after shops.sql and products.sql
                'shippings.sql',
                'taxes.sql',
                'inventories.sql',
                'payments.sql',
                'questions.sql',
                'news.sql',
                'likes.sql',
                'comments.sql',
                'tags.sql',
                'tasks.sql',
                'themes.sql',
                'pages.sql', //dependency - has to run after themes.sql
                'analytics.sql',
                'tutorials.sql',
                'customers.sql',//dependency - has to run after accounts.sql
                'tickets.sql',//dependency - has to run after accounts.sql
                'help.sql',
                'wcm.sql',
                'chatbots.sql',
                'billings.sql',
                'plans.sql',//dependency - has to run after api.sql
            ]
        ];
    }

}