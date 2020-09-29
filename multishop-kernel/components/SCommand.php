<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SCommand
 * 
 * @todo Move this file to multishop-console. Only used in there so far
 * 
 * @author kwlok
 */
class SCommand extends CConsoleCommand 
{
    public function init() 
    {
        parent::init();
    }   
    
    public function logError($msg,$params=array())
    {
        $this->log(CLogger::LEVEL_ERROR, $msg, $params);
    } 
    
    public function logInfo($msg,$params=array())
    {
        $this->log(CLogger::LEVEL_INFO, $msg, $params);
    }  
    
    public function logTrace($msg,$params=array())
    {
        $this->log(CLogger::LEVEL_TRACE, $msg, $params);
    } 
    
    public function log($level,$msg,$params=array()) 
    {
        Yii::log($msg, $level);
        echo "\n".$msg."\n";
        if (!empty($params)){
            $dump = CVarDumper::dump($params, 10);
            if ($level==CLogger::LEVEL_TRACE)
	        Yii::trace($dump);
            else
	        Yii::log($dump, $level);
            echo "\n".$dump."\n";
        }
    }

}
