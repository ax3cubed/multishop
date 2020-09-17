<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.plans.models.Subscription");
/**
 * Description of SubscriptionBaseHandler
 *
 * @author kwlok
 */
abstract class SubscriptionBaseHandler
{
    public $subscription;//susbcription instance
    public $subscriptionManager;//susbcriptionManager instance
        
    public function getSubscriptionModel($notification)
    {
        if (!isset($this->subscription)){
            $model = Subscription::model()->find('subscription_no=\''.$notification->subscription->id.'\'');
            if ($model===null){
                logError(__METHOD__.' subscription not found',$notification->subscription->id,false);
                throw new CHttpException(404,Sii::t('sii','Subscription not found'));
            }
            else {
                $this->subscription = $model;
                logTrace(__METHOD__.' subscription found',$model->attributes);
            }
        }
        return $this->subscription;
    }
    
    public function hasSubscription($notification)
    {
        return $this->getSubscriptionModel($notification)!=null;
    }

    public function getSubscriptionManager()
    {
        if (!isset($this->subscriptionManager))
            $this->subscriptionManager = Yii::app()->serviceManager->getSubscriptionManager();
        return $this->subscriptionManager;
    }
}
