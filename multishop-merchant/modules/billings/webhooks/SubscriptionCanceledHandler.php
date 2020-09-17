<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * A subscription is canceled.
 *
 * @author kwlok
 */
class SubscriptionCanceledHandler extends SubscriptionBaseHandler implements WebhookHandler
{
    public function handleNotification($notification) 
    {
        logInfo(__METHOD__.' start...', $notification->subscription->id);

        if ($this->hasSubscription($notification)){
            $this->getSubscriptionManager()->deactivate($this->subscription);
        }
        else 
            logWarning(__METHOD__.' subscription not found');        
    }
}