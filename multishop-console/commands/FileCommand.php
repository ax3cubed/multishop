<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of FileCommand
 *
 * @author kwlok
 */
class FileCommand extends SCommand 
{
    protected $logFile;
    protected $backupPath;
    public    $backupFilename;
    /**
     * Init
     */
    public function init() 
    {
        parent::init();
        $this->logFile = Yii::app()->runtimePath.DIRECTORY_SEPARATOR.'file_'.date('Ymd').'.log';
        $this->backupPath = param('BACKUP_PATH');
    } 
    /**
     * Backup files  (tar will be in relative path)
     * <Unix command>: tar -zcvf [backup_filename].tar.gz [dir]
     * <Usage>: php console file backup --dir=value
     * 
     * <Use case>: 
     * 1. www/files, 
     * 2. each runtime folder containing logs
     * 3. wcm content files (managed at admin app)
     * 
     * @param string $dir directory name to be backup recursively (absolute path /path/to/dir)
     * @return int
     */
    public function actionBackup($dir,$backupFile=null) 
    {   
        $this->logInfo(__METHOD__.' start..');
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** new backup *****\n", FILE_APPEND);
        
        try {
            //resolve dirname
            $targetdir = array_pop(explode(DIRECTORY_SEPARATOR, $dir));//select target dir name - as the last array element 
            $parentPath = realpath($dir.DIRECTORY_SEPARATOR.'..');
            //$this->logInfo('Parent path: '.$parentPath.', target backup dir: '.$targetdir);
            //IMPORTANT NOTE: MUST change dir before running tar command to get relative path in tar.gz
            chdir($parentPath);

            if (isset($backupFile))
                $this->setBackFilename($backupFile);
            else {
                $rootPath = realpath(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..');
                $relativePath = str_replace($rootPath.'/', '', $dir);//remove also the trailing "/"
                $filename = str_replace('/', '-', $relativePath);//replace "/" with "-"
                //$this->logInfo(__METHOD__.' backup filename is '.$filename);
                $this->setBackFilename($filename.'_'.date('Ymd').'_backup.tar.gz');
            }
                  
            $backupCommand = 'tar -zcvf '.$this->backupFilename. ' '.$targetdir;
            $this->logInfo('Execute: '.$backupCommand);
            $this->logInfo(shell_exec($backupCommand));
            file_put_contents($this->logFile, date('m/d/Y h:i:s a')." backup completed: $this->backupFilename\n", FILE_APPEND);
            $this->logInfo('Completed.');

            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    } 
    /**
     * Restore backup files 
     * <Unix command>: tar -xvzf [backup_filename].tar.gz -C [destination_path]
     * <Usage>: php console file restore --backup=value --destination=value
     * 
     * <Use case>: 
     * 1. Backup of "www/files", e.g. destination_path=/install_base/cdn/www
     * 2. Backup of "wcm content files (managed at admin app)", e.g. destination_path=/install_base/common/modules/wcm
     * 
     * @param type $backup Backup file name (in tar.gz format), and stored in BACKUP_PATH
     * @param type $destination Target destination path
     * @return int
     */
    public function actionRestore($backup,$destination) 
    {   
        $this->logInfo(__METHOD__.' start..');
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** new restore *****\n", FILE_APPEND);
        
        try {
            $backupFile = $this->backupPath.DIRECTORY_SEPARATOR.$backup;
            $backupCommand = 'tar -xvzf '.$backupFile. ' -C '.$destination;
            $this->logInfo('Execute: '.$backupCommand);
            $this->logInfo(shell_exec($backupCommand));
            file_put_contents($this->logFile, date('m/d/Y h:i:s a')." restore $backupFile to destination $destination completed.\n", FILE_APPEND);
            $this->logInfo('Completed.');

            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }     
    /**
     * Purge directory recursively for subdirectories older than certain date 
     * e.g. www/uploads of each app
     * 
     * @param string $dir directory name to be purged recursively (absolute path /path/to/dir)
     * @param int $days direcotory not older than number of days will not be purged
     */
    public function actionPurge($dir,$days)
    {
        $this->logInfo(__METHOD__.' start..');
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** new purge for subdir more than $days days old *****\n", FILE_APPEND);
        $this->logInfo('Purge subdir more than '.$days.' days old');
        
        try {
            
            $today = new DateTime();
            
            if ($handle = opendir($dir)) {
                while (false !== ($subdir = readdir($handle))) {
                    if ($subdir != "." && $subdir != ".." && $subdir != ".DS_Store") {
                        $filedate = new DateTime();
                        $filedate->setTimestamp(filemtime($dir.DIRECTORY_SEPARATOR.$subdir));
                        $interval = $today->diff($filedate);
                        if ($interval->days > $days) {
                            $this->logInfo("Purge: ".$dir.DIRECTORY_SEPARATOR.$subdir.' '.$interval->format('%R%a days old'));
                            CFileHelper::removeDirectory($dir.DIRECTORY_SEPARATOR.$subdir);
                            file_put_contents($this->logFile, date('m/d/Y h:i:s a').' purge completed: '.$dir.DIRECTORY_SEPARATOR.$subdir.' '.$interval->format('%R%a days old')."\n", FILE_APPEND);
                        }
                        else {
                            $this->logInfo('Skip: '.$dir.DIRECTORY_SEPARATOR.$subdir.' '.$interval->format('%R%a days old'));
                        }                    
                    }
                }
                closedir($handle);
            }

            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }    
    
    public function setBackFilename($filename)
    {
        $this->backupFilename = $this->backupPath.DIRECTORY_SEPARATOR.$filename;
    }
    
}
