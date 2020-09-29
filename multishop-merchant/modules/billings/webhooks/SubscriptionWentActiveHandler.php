<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * A subscription's first authorized transaction is created. 
 * Subscriptions with trial periods will never trigger this notification.
 * 
 * Two triggering scenario:
 * (1) When first time went active
 * (2) When transit from pastdue to active
 * 
 * @author kwlok
 */
class SubscriptionWentActiveHandler extends SubscriptionBaseHandler implements WebhookHandler
{
    public function handleNotification($notification) 
    {
        logInfo(__METHOD__.' start...', $notification->subscription->id);
        
        if ($this->hasSubscription($notification)){
            $this->getSubscriptionManager()->activate($this->subscription);
        }
        else 
            logWarning(__METHOD__.' subscription not found');
    }

}