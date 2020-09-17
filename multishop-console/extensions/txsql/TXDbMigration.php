<?php
Yii::import('console.extensions.txsql.TXFile');
Yii::import('console.extensions.txsql.TXFsObject');
Yii::import('console.extensions.txsql.TXObject');
/**
 * A helper to execute a .sql file inside a migration.
 */
class TXDbMigration extends CDbMigration 
{
    const SQL_COMMAND_DELIMETER = ';';
    const SQL_COMMENT_SYMBOL    = '--';
    protected $suppressInfo = false;
    
    protected function _infoLine($filePath, $next = null) 
    {
        if (!$this->suppressInfo)
            echo "\r    > execute file $filePath ..." . $next;
    }
    /**
     * To print line to console
     * @param type $filePath
     * @param type $next
     */
    protected function printLine($filePath, $next = null) 
    {
        $this->_infoLine($filePath, $next);
    }
  
    public function executeFile($filePath) 
    {
        $this->_infoLine($filePath);
        $time=microtime(true);
        $file = new TXFile(array(
          'path' => $filePath,
        ));

        if (!$file->exists)
            throw new Exception("'$filePath' is not a file");

        try {
            
            if ($file->open(TXFile::READ) === false)
                throw new Exception("Can't open '$filePath'");

            $total = floor($file->size / 1024);
            $command='';
            while (!$file->endOfFile()) {
                $line = $file->readLine();
                $line = trim($line);
                if (empty($line))
                    continue;
                $current = floor($file->tell() / 1024);
                $this->_infoLine($filePath, " $current of $total KB");
                $command .= $line;
                if (strpos($line,self::SQL_COMMAND_DELIMETER)){
                    if (substr($command, 0, strlen(self::SQL_COMMENT_SYMBOL))==self::SQL_COMMENT_SYMBOL){
                        //$this->_infoLine($filePath, " skip comment:  ".$command."\n");
                    }
                    else {
                        $this->printLine($filePath, " executing command:  ".$command."\n");
                        $this->getDbConnection()->createCommand($command)->execute();
                    }
                    $command = '';
                }
            }
            $file->close();
          
        } catch (Exception $e) {
            $file->close();
            var_dump($line);
            throw $e;
        }
        $this->printLine($filePath, " done (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n");
    }
  
}