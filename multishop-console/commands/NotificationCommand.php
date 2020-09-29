<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("console.commands.NotificationBaseCommand");
/**
 * Description of NotificationCommand
 * 
 * @author kwlok
 */
class NotificationCommand extends NotificationBaseCommand 
{
    /**
     * Run default command
     * @param $type
     */
    public function actionIndex($type=null) 
    {   
        //$this->logTrace(__METHOD__.' start..');
        $count=0;
        $errCount=0;
        $this->constructQueue($type);
        while ($this->queue->count() > 0 ) {
            $item = $this->queue->dequeue();
            $count++;

            switch ($item->type) {
                case Notification::$typeEmail:
                    $status = $this->flushMessage($item, 'sendEmail');
                    break;
                case Notification::$typeMessenger:
                    $status = $this->flushMessage($item, 'messenger');
                    break;
                default:
                    break;
            }
            
            if (isset($status)){//make sure message is flushed then only check status
                if ($status==Process::OK)
                    $this->logInfo('['.$count.'] Message "'.$item->id.'" processed ok');
                else{
                    $this->logError('['.$count.'] Message "'.$item->id.'" processed error',$item->getAttributes());
                    $errCount++;
                }
            }
            
        }
        if ($count>0)
            $this->logInfo(__METHOD__.' success['.($count-$errCount).'] error['.$errCount.'] end.');

        return 0;        
    }  
    /**
     * Purging message queue (for those successfully sent out)
     * @return int
     */
    public function actionPurge($type=null) 
    {   
        if (!isset($type))
            $type = Notification::$typeEmail;
        
        try {
            $count = MessageQueue::model()->purgelist($type)->count();
            MessageQueue::model()->purgelist($type)->deleteAll(); 
            $this->logInfo(__METHOD__.' Total '.$count.' processed messages purged.');
            return 0;//success
        } catch (CException $e) {
            $this->logError(__METHOD__.' '.$e->getMessage());
            return 1;//error
        }
    }   
    /**
     * Construct queue according to batch size
     * @see $batchSize
     * @param type $type
     */
    private function constructQueue($type=null)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = self::$batchSize;
        $mq = MessageQueue::model()->waitlist($type)->findAll($criteria);
        foreach ($mq as $message)
            $this->queue->enqueue($message);
        if ($this->queue->getCount()>0)
            $this->logTrace(__METHOD__.' Total batch items = '.$this->queue->getCount());
    }
    /**
     * Flush the message out (set status to OK)
     * @param type $item
     * @param type $method
     */
    private function flushMessage($item,$method)
    {
        try {
            $message = json_decode($item->message);
            $message->mode = NotificationEvent::SYNCHRONOUS;
            $item->status = $this->dispatcher->{$method}($message);
            $item->update();
            return $item->status;
            
        } catch (Exception $ex) {
            $this->logError(__METHOD__.' error >> '.$ex->getTraceAsString());
        }
    }
}
