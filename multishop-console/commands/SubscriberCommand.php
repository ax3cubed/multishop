<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("console.commands.NotificationBaseCommand");
Yii::import("common.services.notification.Messenger");
/**
 * Description of SubscriberCommand
 * For sending notification to subsCribers
 * 
 * @author kwlok
 */
class SubscriberCommand extends NotificationBaseCommand 
{
    /**
     * Send daily notifications 
     * @return int
     */
    public function actionDaily($channel=null) 
    {   
        //$this->logTrace(__METHOD__.' start..');
        $count=0;
        $errCount=0;
        $successCount=0;
        $this->constructQueue($channel,'newDailyBatch');
        while ($this->queue->count() > 0 ) {
            $subscription = $this->queue->dequeue();
            $count++;
                    
            foreach ($subscription->nonEventNotifications as $notification) {

                //only sends when subscription channel matches notification name and type
                if ($subscription->canStart && 
                    $subscription->notification==$notification->name && 
                    $subscription->channel==$notification->type){

                    if ($notification->type==Notification::$typeEmail 
                        && isset($subscription->subscriberEmail)){
                        $status = $this->notifyByEmail($notification, $subscription);
                    }            
                    elseif ($notification->type==Notification::$typeMessenger && 
                            isset($subscription->subscriberMessenger)){
                        $status = $this->notifyByMessenger($notification, $subscription);
                    }            

                    $this->logStatus($notification, $status, $successCount);
                    if ($status==Process::ERROR)
                        $errCount++;
                    
                    if ($status==Process::OK){
                        $subscription->setDailyBatchAsRun();
                        $successCount++;
                    }                    
                }
            }
            
        }
        if ($count>0)
            $this->logInfo(__METHOD__.' batch['.($count).'] success['.($successCount-$errCount).'] error['.$errCount.'] end.');
        return 0;        
    }  
    /**
     * Purging unsubscribed notifications
     * @return int
     */
    public function actionPurge() 
    {   
        try {
            $count = NotificationSubscription::model()->unsubscribed()->count();
            NotificationSubscription::model()->unsubscribed()->deleteAll(); 
            $this->logInfo(__METHOD__.' Total '.$count.' unsubscribed notifications purged.');
            return 0;//success
        } catch (CException $e) {
            $this->logError(__METHOD__.' '.$e->getMessage());
            return 1;//error
        }
    }   
    /**
     * Construct queue according to batch size
     * @see $batchSize
     */
    private function constructQueue($channel=null,$scope=null)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = self::$batchSize;
        
        $finder = NotificationSubscription::model()->subscribed();
        if (isset($channel))
            $finder = $finder->notifyBy($channel);
        if (isset($scope))
            $finder = $finder->{$scope}();
        
        $subscriptions = $finder->findAll($criteria);
        foreach ($subscriptions as $subscription)
            $this->queue->enqueue($subscription);
        if ($this->queue->getCount()>0)
            $this->logTrace(__METHOD__.' Total batch items = '.$this->queue->getCount());
    }
    /**
     * Simple status logging
     * @param type $notification
     * @param type $status
     * @param type $count
     */
    private function logStatus($notification,$status,$count)
    {
        if ($status==Process::OK){
            $this->logInfo("[$count] Send notification '$notification->name' type $notification->type ok");
        }
        else {
            $this->logError("[$count] Error send notification '$notification->name' type $notification->type error");
        }
    }
    /**
     * Send notification by email
     * Not immediately send it but put into message queue and let NotificationCommand do the sending
     * Note: Non-event notifications template view data are referenced on subscription view data
     * 
     * @param type $notification
     * @param type $subscription
     * @return type
     */
    private function notifyByEmail($notification,$subscription)
    {
        $subject = Notification::parseSubject('static', $notification->subject);

        $content = $this->controller->renderPartial($notification->content,$subscription->getParams(),true);

        //set ASYNCHRONOUS so that put into message queue and let NotificationCommand do the sending
        $event = new EmailEvent($subscription->subscriberEmail,
                                $subscription->subscriberName,
                                $subject,
                                $content,
                                EmailEvent::ASYNCHRONOUS);

        return $this->dispatcher->email($event);
    }
    /**
     * Send notification by messenger (not immediately send it)
     * 
     * Note: Non-event notifications template view data are referenced on subscription view data
     * 
     * @param type $notification
     * @param type $subscription
     * @return type
     */
    private function notifyByMessenger($notification,$subscription)
    {
        //not use
        //$subject = Notification::parseSubject('static', $notification->subject);

        $content = isset($notification->content)?$this->controller->renderPartial($notification->content,$subscription->getParams(),true):null;
        if (isset($content)){
            $subData = json_decode($content, true);
            $event = new MessengerEvent(
                $subscription->scope,
                $subscription->subscriberMessenger,
                $subData['payload'],
                Messenger::TYPE_PAYLOAD,
                array_merge($subData['params'],$subscription->getParams()),
                MessengerEvent::ASYNCHRONOUS);//put into message queue and let NotificationCommand do the sending

            return $this->dispatcher->messenger($event);
        }
    }    
}
