<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * A subscription has moved from the active status to the past due status. 
 * This occurs when a subscription’s initial transaction is declined.
 * 
 * Braintree Note:
 * If a payment for a subscription fails, the subscription status will change to Past Due. 
 * You can manually retry the charge, or you can set up logic in the Control Panel to automatically retry a declined or failed charge at specific intervals. 
 * Additionally, a past due subscription will be automatically retried if the payment method associated with the subscription 
 * is updated and you either have proration enabled or are manually passing the prorate charge option. 
 * If the retried transaction is successful and the subscription has not passed its last billing date, the status will change to Active.
 * 
 * @author kwlok
 */
class SubscriptionPastDueHandler extends SubscriptionBaseHandler implements WebhookHandler
{
    public function handleNotification($notification) 
    {
        logInfo(__METHOD__.' start... daysPastDue='.$notification->subscription->daysPastDue, $notification->subscription->id);
        
        if ($this->hasSubscription($notification)){
            $this->getSubscriptionManager()->pastdue($this->subscription,$notification->subscription->daysPastDue);
        }
        else 
            logWarning(__METHOD__.' subscription not found');    
    }

}