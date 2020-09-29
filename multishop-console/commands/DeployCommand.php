<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('console.commands.FileCommand');
/**
 * Description of DeployCommand
 * It does the git fectch and checkout and auto refresh working dir
 * 
 * @author kwlok
 */
class DeployCommand extends SCommand 
{
    /*
     * Full path to git binary is required if git is not in your PHP user's path. 
     * Otherwise just use 'git'.
     */ 
    public $gitBinPath = '/usr/bin/git';
    public $gitVersionFile = 'git.version';

    protected $configJsonBackup;
    /**
     * Default index command
     * @param type $jsonFile The config.json file path to read
     * @param type $payloadFile Sent from git (e.g. bitbucket or github) webhook to notify repository update
     * @return int
     */
    public function actionIndex($jsonFile=null, $payloadFile=null) 
    {   
        $this->logInfo(__METHOD__.' start..');
        //load configuration
        $git_bin_path = $this->gitBinPath;
        $git_dir = readConfig('system', 'gitDir',$jsonFile);
        $git_work_tree = readConfig('system', 'gitWorkTree',$jsonFile);
        $git_branch = readConfig('system', 'gitBranch',$jsonFile);
        $logfile = Yii::app()->runtimePath.DIRECTORY_SEPARATOR.'deploy_'.date('Ymd').'.log';
        file_put_contents($logfile, date('m/d/Y h:i:s a')." ***** new deploy $git_work_tree *****\n", FILE_APPEND);
        $update = isset($payloadFile)?false:true;//for payload mode, set to false first
        
        //backup all necessary files
        $this->backupFiles($jsonFile);
        
        try {
            //load payload if file exists
            if (isset($payloadFile)){
                $payload = file_get_contents($payloadFile);
                if ($payload!=false){
                    $payload = json_decode($payload, true);
                    file_put_contents($logfile, var_export($payload,true)."\n", FILE_APPEND);
                    if (empty($payload['commits'])){
                      // When merging and pushing to bitbucket, the commits array will be empty.
                      // In this case there is no way to know what branch was pushed to, so we will do an update.
                      $update = true;
                    } 
                    else {
                        foreach ($payload['commits'] as $commit) {
                            $branch = $commit['branch'];
                            $count = 0;
                            foreach($commit['files'] as $changed){
                                $count++;
                                file_put_contents($logfile, "  ".$count." => ".$changed->file. " (".$changed->type. ")\n", FILE_APPEND);
                            }
                            $node = $commit['node'];
                            if ($branch === $git_branch || isset($commit['branches']) && in_array($git_branch, $commit['branches'])) {
                                file_put_contents($logfile, date('m/d/Y h:i:s a')." update is true, commit = ".$node."\n", FILE_APPEND);
                                $update = true;
                                break;
                            }
                        }
                    }
                }
            }
            
            if ($update) {
                //[1] Do a git fetch 
                $gitFetchCommand = $git_bin_path  . ' --git-dir='.$git_dir. ' fetch';
                $this->logInfo('gitFetchCommand = '.$gitFetchCommand);
                $this->logInfo(shell_exec($gitFetchCommand));
                //[2] Do a git checkout
                $gitCheckoutCommand = $git_bin_path  . ' --git-dir='.$git_dir. ' --work-tree=' . $git_work_tree . ' checkout -f '.$git_branch;
                $this->logInfo('gitCheckoutCommand = '.$gitCheckoutCommand);
                $this->logInfo(shell_exec($gitCheckoutCommand));
                //[3] Log the deployment
                $gitHeadCommand = $git_bin_path  . ' --git-dir='.$git_dir. ' rev-parse --short HEAD';
                $commit_hash = shell_exec($gitHeadCommand);
                $current_version = Helper::getGitCommitHash($git_work_tree,$this->gitVersionFile);//refer format at git.version
                $this->logInfo('commit_hash:'.trim($commit_hash).' vs current_version:'.$current_version);
                if (trim($commit_hash)==$current_version){
                    //[4.1] skip git info update
                    file_put_contents($logfile, date('m/d/Y h:i:s a')." no change for $git_work_tree branch:".$git_branch." commit:".$commit_hash."\n", FILE_APPEND);
                    $this->logInfo("no change for $git_work_tree branch:".$git_branch);
                }
                else {
                    file_put_contents($logfile, date('m/d/Y h:i:s a')." Deploy $git_work_tree branch:".$git_branch." commit:".$commit_hash."\n", FILE_APPEND);
                    //[4.2] update git info file
                    //Format: repo_name/branch_name/commit_hash
                    $gitVersionfile = $git_work_tree.DIRECTORY_SEPARATOR.$this->gitVersionFile;
                    //Retrieve repo name
                    $path = explode(DIRECTORY_SEPARATOR, $git_work_tree);
                    $gitRepo = end($path);//repo name is at the last element of array
                    file_put_contents($gitVersionfile, $gitRepo.'/'.$git_branch.'/'.trim($commit_hash).','.time());
                    $this->logInfo("Deploy repo:$gitRepo branch:".$git_branch." commit:".$commit_hash);
                }
                
                //Restore files
                $this->restoreFiles($jsonFile);
                
            }
            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
        
    }  
//    
//    public function actionTest() 
//    {  
//        $this->logInfo(__METHOD__.' start..');
//        $this->backupFiles();
//        sleep(20);
//        $this->restoreFiles();
//        return 0;
//    }
    /**
     * Backup config.json if not done
     */
    protected function backupFiles($jsonFile=null)
    {
        if ($jsonFile==null)
            $jsonFile = APP_CONFIG;
        //[1]backup config.json
        $this->configJsonBackup = $jsonFile.'.bak';
        if (!file_exists($this->configJsonBackup))
            copy($jsonFile, $this->configJsonBackup);
    }
    /**
     * Restore config.json backup as newly fetched config.json will not have settings
     * Restore wcm content
     */
    protected function restoreFiles($jsonFile=null)
    {
        if ($jsonFile==null)
            $jsonFile = APP_CONFIG;
        
        //[1]restore config.json
        if (file_exists($jsonFile))
            copy($this->configJsonBackup, $jsonFile);
    }
}
