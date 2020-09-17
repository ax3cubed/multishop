<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of BraintreeController
 * Handles braintree webhooks
 * 
 * @author kwlok
 */
class BraintreeController extends SController 
{
    /**
     * Init class
     */
    public function init()
    {
        $this->loadBraintreeClasses();
    }
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', 
        ];
    }
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow',  
                'actions'=>['webhook'],
                'users'=>['*'],
            ],
            //default deny all users anything not specified       
            ['deny',  
                'users'=>['*'],
            ],
        ];
    }
    
    public function actionWebhook()
    {
        if (isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) {
            
            $webhookNotification = Braintree\WebhookNotification::parse(
                $_POST["bt_signature"], $_POST["bt_payload"]
            );
            
            logTrace(__METHOD__.' notification received',$webhookNotification);           
            $message = "Received: " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . " | Kind: " . $webhookNotification->kind;
            logInfo(__METHOD__.' '.$message);
            
            $this->handleNotification($webhookNotification);
            
            Yii::app()->end();
        } 
        else
            throwError403(Sii::t('sii','Unauthorized Access'));
    }
    
    protected function handleNotification($notification)
    {
        switch ($notification->kind) {
            case Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY:
                $handler = new SubscriptionChargedSuccessfulHandler();
                break;
            case Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY:
                $handler = new SubscriptionChargedUnsuccessfulHandler();
                break;
            case Braintree\WebhookNotification::SUBSCRIPTION_CANCELED:
                $handler = new SubscriptionCanceledHandler();
                break;
            case Braintree\WebhookNotification::SUBSCRIPTION_WENT_ACTIVE:
                $handler = new SubscriptionWentActiveHandler();
                break;
            case Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE:
                $handler = new SubscriptionPastDueHandler();
                break;
            default:
                logError(__METHOD__.' unsupported notification kind '.$notification->kind);
                //break;
                return;
        }

        $handler->handleNotification($notification);
    }
    
    protected function loadBraintreeClasses()
    {
        //dummpy method to load braintree classes
        $braintree = $this->module->braintreeApi;
    }
}
