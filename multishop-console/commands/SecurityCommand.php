<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SecurityCommand
 *
 * @author kwlok
 */
class SecurityCommand extends SCommand 
{
    protected $logFile;
    /**
     * Init
     */
    public function init() 
    {
        parent::init();
        $this->logFile = Yii::app()->runtimePath.DIRECTORY_SEPARATOR.'security_'.date('Ymd').'.log';
    }      
    /**
     * A simple method to get encrypted value
     * @param type $data
     */
    public function actionEncrypt($data)
    {
        echo "\ndata:$data , encrypted_value: ".SSecurityManager::encryptData($data)."\n";
    }    
    /**
     * A simple method to get decrypted value
     * @param type $data
     */
    public function actionDecrypt($data)
    {
        echo "\ndata:$data , decrypted_value: ".SSecurityManager::decryptData($data)."\n";
    }    
    
}
