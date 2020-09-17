<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CommandLogFileTrait
 *
 * @author kwlok
 */
trait CommandLogFileTrait 
{
    protected $logFileName = 'command';
    protected $logFile;
    /**
     * Init
     */
    public function init() 
    {
        parent::init();   
        $this->logFile = Yii::app()->runtimePath.DIRECTORY_SEPARATOR.$this->logFileName.'_'.date('Ymd').'.log';
        $this->writeLog("*** BEGIN LOGGING *** ",false);
    } 

    public function writeLog($message,$echoMessage=true,$level=CLogger::LEVEL_INFO,$params=array())
    {
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." $message\n", FILE_APPEND);
        if ($echoMessage)
            $this->log($level,$message,$params);
    }
    
    public function writeLogDump($message,$params=array(),$level=CLogger::LEVEL_INFO)
    {
        $this->writeLog($message,true,$level,$params);
    }    
    
}
