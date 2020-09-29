<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ElasticSearchCommand
 * 
 * A console command provides access to common.modules.search.components.ElasticSearch
 * 
 * @see ActiveESClient
 * @author kwlok
 */
class ElasticSearchCommand extends SCommand 
{   
    public $elasticsearch;
    /**
     * Initializes the command object.
     */
    public function init()
    {
        parent::init(); 
        //load ElasticSearch component from SearchModule
        $searchModule = Yii::app()->getModule('search');
        $this->elasticsearch = $searchModule->getElasticSearch();
    }
    /**
     * Default search based on model and query
     * @param type $model
     * @param type $query
     */
    public function actionIndex($model,$query) 
    {
        $this->elasticsearch->setMatchQuery($query);
        try {
            $records = $this->elasticsearch->search($model); 
            if ($records!=null && is_array($records)){
                foreach ($records as $record) {
                    $this->_printArray($record);
                }
            }
            else
                $this->_printLine("Error: $model results is null");
            
        } catch (Exception $ex) {
            $this->_printLine('Error: "'.$ex->getMessage().'"');
            
        }
    }
    /**
     * Create model index (delete all first then index again)
     * 
     * Run command:  php console elasticsearch create --model=value
     * 
     * @param type $model
     * @throws CExeption
     */
    public function actionCreate($model) 
    {
        $data = new CList();
        $_tmpModel = new $model();
        $arModel = $_tmpModel->arClass;
        foreach ($arModel::model()->findAll() as $_m) {
            $_p = new $model();
            $_p->assignAttributes($_m);
            $data->add($_p);
        }
        $this->elasticsearch->save($data->toArray(),true); //set refresh to true
        $this->_printLine('Model '.$model.' created successfully.');
    }
    /**
     * Exists model 
     * 
     * Run command:  php console elasticsearch exists --model=value --id=value
     * 
     * @param type $model
     * @throws CExeption
     */
    public function actionExists($model,$id) 
    {
        $searchModel = new $model();
        $searchModel->primaryKey = $id;
        $arModel = $searchModel->model;
        if ($arModel==null){
            $this->_printLine("Error: ActiveRecord model with id = $id not found");
            return;
        }
        $searchModel->assignAttributes($arModel);
        if ($this->elasticsearch->exists($searchModel))
            $this->_printLine("Search Model $model with id = $id exists.");
        else
            $this->_printLine("Search Model $model with id = $id not found.");
    }
    /**
     * Delete all model 
     * 
     * Run command:  php console elasticsearch delete --model=value
     * 
     * @param type $model
     * @throws CExeption
     */
    public function actionDeleteAll($model) 
    {
        $n = $this->elasticsearch->deleteAll($model);
        $this->_printLine('Model '.$model.' ('.$n.') deleted successfully.');
    }
    /**
     * This command wrapper uses elasticsearch SNAPSHOT API to back up elasticsearch cluster
     * This will take the current state and data in your cluster and save it to a shared repository. 
     * This backup process is "smart." Your first snapshot will be a complete copy of data, but all subsequent snapshots will save the delta between the existing snapshots and the new data. 
     * Data is incrementally added and deleted as you snapshot data over time. This means subsequent backups will be substantially faster since they are transmitting far less data.
     *
     * A repository can contain multiple snapshots. 
     * Each snapshot is associated with a certain set of indices (for example, all indices, some subset, or a single index).
     * 
     * For now, this wrapper backup all indices of a cluster
     * 
     * @param type $repo name of backup repository; Default to "cluster name_repo"
     * @param type $snapshot snapshot unique name
     * @return int
     */
    public function actionBackup($repo=null,$snapshot=null)
    {
        $this->logInfo(__METHOD__.' start..');
        try {

            $repoParams = $this->initBackupRepo($repo);
                        
            if (isset($snapshot)){
                $this->logInfo("Create snapshot[$snapshot] for backup repo $repoParams->name ...");
                $this->_curl($repoParams->url.'/'.$snapshot, 'PUT');
            }
            
            $this->logInfo('Completed');

            return 0;   
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }
    /**
     * This command wrapper uses elasticsearch SNAPSHOT API to restore elasticsearch cluster
     * The default behavior is to restore all indices that exist in that snapshot. 
     * If snapshot_1 contains five indices, all five will be restored into our cluster. 
     * As with the snapshot API, it is possible to select which indices we want to restore.
     * 
     * @param type $snapshot snapshot unique name
     * @param type $repo name of backup repository; Default to "cluster name_repo"
     * @return int
     */
    public function actionRestore($snapshot,$repo=null)
    {
        $this->logInfo(__METHOD__.' start..');
        try {

            $repoParams = $this->initBackupRepo($repo);
            
            $this->logInfo("Restoring snapshot[$snapshot] for $repoParams->name ...");
            
            $this->_curl($repoParams->url.'/'.$snapshot.'/_restore', 'POST');
            
            $this->logInfo('Completed');

            return 0;   
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }
    /**
     * Init back up repository
     * If not found, create a new one
     * 
     * @param string $name
     * @return type
     */
    private function initBackupRepo($name=null)
    {
        if (!isset($name)){
            $name = readConfig('elasticsearch','clusterName').'_repo';
            $this->logInfo("Use default backup name $name ...");
        }

        $url = 'http://'.readConfig('elasticsearch','host').':'.readConfig('elasticsearch','port').'/_snapshot/'.$name;
        $response = $this->_curl($url, 'GET');

        if (isset($response['status']) && $response['status']=='404'){
            $this->logInfo("Create new one...");
            $params = '{"type": "fs","settings": {"compress":true,"location": "'.readConfig('system','backupDir').DIRECTORY_SEPARATOR.$name.'"}}';
            $response = $this->_curl($url, 'PUT', $params);
        }
        
        return (object)array(
            'name'=>$name,
            'url'=>$url
        );
    }
    /**
     * Print a message line
     * @param string $message
     */
    private function _printLine($message)
    {
        echo $message."\n";
    }
    /**
     * Print an array
     * @param array $array
     */
    private function _printArray($array)
    {
        echo CVarDumper::dumpAsString($array, 10) ;
    }
    /**
     * Run curl command
     * @param array $array
     */
    private function _curl($url,$method,$params='{}')
    {
        //setting the curl parameters. 
        $handle = curl_init(); 
        $this->logInfo('Calling '.$url);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS 
            CURLOPT_POSTFIELDS => $params,//json 
            CURLOPT_RETURNTRANSFER => 1,//it will return the result on success, FALSE on failure.
          ); 
        curl_setopt_array($handle,$options); 
        // send request and wait for response
        $response = json_decode(curl_exec($handle),true);
        $this->_printArray($response);
        curl_close($handle); 
        return $response;
    }
    
}
