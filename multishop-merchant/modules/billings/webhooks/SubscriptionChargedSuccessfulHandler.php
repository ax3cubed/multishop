<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.payments.plugins.braintreeCreditCard.components.BraintreeApiTrait');
/**
 * A subscription successfully moves to the next billing cycle. 
 * This occurs if a new transaction is created. 
 * It will also occur when a billing cycle is skipped due to the presence of a negative balance 
 * that covers the cost of the subscription. 
 * 
 * @author kwlok
 */
class SubscriptionChargedSuccessfulHandler extends SubscriptionBaseHandler implements WebhookHandler
{
    use BraintreeApiTrait;
    
    public function handleNotification($notification) 
    {
        $subscriptionId = $notification->subscription->id;
        $billingPeriodStartDate = $notification->subscription->billingPeriodStartDate->format('Y-m-d');
        $billingPeriodEndDate = $notification->subscription->billingPeriodEndDate->format('Y-m-d');
        $message = $subscriptionId.' billingPeriodStartDate: '.$billingPeriodStartDate.', billingPeriodEndDate: '.$billingPeriodEndDate;
        logInfo(__METHOD__.' '. $message);
        
//        if ($notification->subscription->trialPeriod && isset($notification->subscription->trialDuration)){
//            logInfo(__METHOD__.' in trial period, skip!');
//            return false;
//        }
        
        $model = Subscription::model()
                    ->subscriptionNo($subscriptionId)
                    ->billingPeriod($billingPeriodStartDate,$billingPeriodEndDate)
                    ->find();
        if ($model!=null){//found
            logTrace(__METHOD__.' model attributes',$model->attributes);
            if (!$model->isCharged){
                logTraceDump(__METHOD__.' transactions',$notification->subscription->transactions);
                $this->getSubscriptionManager()->charge($model,$this->constructTransactionData($notification->subscription->transactions));
            }
            else 
                logWarning(__METHOD__.' subscription was charged before! skip.');
        }
        else {
            logInfo(__METHOD__.' new billing cycle! create new billing period subscription...');
            $this->getSubscriptionManager()->renew(
                $subscriptionId,
                $billingPeriodStartDate,
                $billingPeriodEndDate,
                $this->constructTransactionData($notification->subscription->transactions));
        }
    }
    /**
     * @see BraintreeApiTrait::createTraceNo()
     * @param type $transactions
     */
    public function constructTransactionData($transactions)
    {
        $data = [];
        foreach ($transactions as $transaction){
            $data[] = $this->createTraceNo($transaction);
            break;//always take the latest transaction; Braintree returns all transaction history.
        }
        logTrace(__METHOD__,$data);
        return $data;
    }

}
