<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('console.components.CommandLogFileTrait');
Yii::import('console.components.ConsoleController');
Yii::import('common.modules.plans.models.*');
Yii::import('common.modules.notifications.models.*');
/**
 * Description of SubscriptionCommand
 *
 * @author kwlok
 */
class SubscriptionCommand extends SCommand 
{
    use CommandLogFileTrait {
        init as traitInit;
    }          
    /**
     * Init
     */
    public function init() 
    {
        $this->logFileName = 'subscription';
        $this->traitInit();
    } 
    /**
     * Scan going to expired subscription (FREE TRIAL) and send out reminder 
     * Note: Setup a cronjob to scan everyday
     * [1] Trigger reminder on every 30th day before expiry
     * [2] Trigger reminder on 14th day before expiry
     * [3] Trigger reminder on 3rd day before expiry
     * [4] Trigger reminder on last day before expiry
     */
    public function actionFreeTrial() 
    { 
        $this->writeLog('Start scanning..');
        $noticePeriod = [1, 3,14,30 ];//days
        $today = new DateTime("now");
        $controller = new ConsoleController('notification');

        try {
            
            foreach (Subscription::model()->freeTrial()->notExpired()->findAll() as $subscription) {
                $expiry = new DateTime($subscription->end_date);
                $diff = $expiry->diff($today);
                foreach ($noticePeriod as $period) {
                    if ($diff->y < 1 && $diff->m < 1 &&  $diff->d == $period){//only pick up within one month, and within same year
                        $this->writeLog("subscription $subscription->id has pending $period days to expire.\n");
                        $reminder =  new Reminder();
                        $reminder->recipient = $subscription->account_id;
                        $reminder->subject = Sii::t('sii','Reminder: Your free trial subscription is expiring in {n} days.',['{n}'=>$period]);
                        $reminder->content = $controller->renderPartial('common.modules.notifications.templates.email.subscription.reminder',['model'=>$subscription,'days'=>$period],true);
                        Yii::app()->serviceManager->getNotificationManager()->send($reminder);
                        break;
                    }
                }
            }
            
            $this->writeLog("Completed");

            return 0;        
            
        } catch (CException $e) {
            $this->writeLog('error: '.$e->getTraceAsString());
            return -1;
        }
    }    
    /**
     * House keeping
     * Scan expired subscription and set status to EXPIRED
     * Suggest to run every one week
     */
    public function actionHousekeeping()
    {
        $this->writeLog('Start scanning..');
        try {
            
            //scan those expired but status is Active (abnormal case, but preventive approach)
            foreach (Subscription::model()->expired()->active()->findAll() as $subscription) {
                $this->writeLog("Caught! subscription $subscription->id is active but expired; status=$subscription->status\n");
                //todo below is manual approach as having technical issue to call SubscriptionManager/WorkflowManager (unless got more use cases then need to do it proper)
                $subscription->status = Process::SUBSCRIPTION_EXPIRED;
                $subscription->update();
                $this->writeLog(" Force set subscription $subscription->id status to expired; status=$subscription->status\n");
                $subscription->revokePermission($subscription->account_id,$subscription->plan->name,'EXPIRE');
                $this->writeLog(' Revoke permission '.$subscription->plan->name." \n");
            }
            
            $this->writeLog("Completed");

            return 0;        
            
        } catch (CException $e) {
            $this->writeLog('error: '.$e->getTraceAsString());
            return -1;
        }        
    }
    
}
