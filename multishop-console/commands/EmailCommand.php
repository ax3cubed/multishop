<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.services.notification.Dispatcher");
Yii::import("common.services.notification.events.EmailEvent");
Yii::import("common.modules.tasks.models.Process");
Yii::import('console.components.CommandLogFileTrait');
/**
 * Description of EmailCommand
 * 
 * @author kwlok
 */
class EmailCommand extends SCommand 
{
    use CommandLogFileTrait {
        init as traitInit;
    }     
    /**
     * Init
     */
    public function init() 
    {
        $this->logFileName = 'email';
        $this->traitInit();
    }      
    /**
     * Test send email
     * 
     * @param string $addressTo Recipient email address
     * @param string $addressName Recipient name
     * @param string $subject Email subject
     * @param string $content Email content
     * @param string $attachment 
     * @param string $senderName 
     * @return int
     */
    public function actionTest($addressTo,$addressName='Test Recipient',$subject='Test email subject',$content='Test email content',$senderName='Multishop EmailCommand')
    {   
        $this->logInfo(__METHOD__.' start..');
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** test email *****\n", FILE_APPEND);
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." Test email send to: $addressTo\n", FILE_APPEND);
        
        try {
            $this->logInfo("Sending email...\n");
            $this->logInfo(" senderName: $senderName \n");
            $this->logInfo(" addressTo: $addressTo \n");
            $this->logInfo(" addressName: $addressName \n");
            $this->logInfo(" subject: $subject \n");
            $this->logInfo(" content: $content \n");
            $result = Dispatcher::sendEmail(new EmailEvent(
                                    $addressTo,
                                    $addressName,
                                    $subject,
                                    $content,
                                    EmailEvent::SYNCHRONOUS,
                                    null,//no attachement
                                    $senderName));
            if ($result==Process::OK){
                file_put_contents($this->logFile, date('m/d/Y h:i:s a')." Email sent.\n", FILE_APPEND);
                $this->logInfo('Done!');
            }
            else {
                file_put_contents($this->logFile, date('m/d/Y h:i:s a')." Error!\n", FILE_APPEND);
                $this->logInfo('Error!');
            }        
            
            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }      

}