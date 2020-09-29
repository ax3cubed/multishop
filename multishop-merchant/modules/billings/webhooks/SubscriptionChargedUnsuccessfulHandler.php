<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * A subscription already exists and fails to create a successful charge. 
 * This will not trigger on manual retries or if the attempt to create a subscription fails due to an unsuccessful transaction.
 * 
 * @author kwlok
 */
class SubscriptionChargedUnsuccessfulHandler extends SubscriptionBaseHandler implements WebhookHandler
{
    public function handleNotification($notification) 
    {
        logInfo(__METHOD__.' start... daysPastDue='.$notification->subscription->daysPastDue, $notification->subscription->id);

        if ($this->hasSubscription($notification)){
            $this->getSubscriptionManager()->suspend($this->subscription,$notification->subscription->daysPastDue);
        }
        else
            logWarning(__METHOD__.' subscription not found');
    }

}