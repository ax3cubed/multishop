<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('console.components.CommandLogFileTrait');
Yii::import('common.modules.payments.plugins.braintreeCreditCard.components.BraintreeApiTrait');
/**
 * Description of BraintreeWebhookCommand
 *
 * @author kwlok
 */
class BraintreeWebhookCommand extends SCommand 
{
    use BraintreeApiTrait;
    use CommandLogFileTrait {
        init as traitInit;
    }  
    private $_braintreeApi;
    /**
     * Init
     */
    public function init() 
    {
        $this->logFileName = 'braintree_webhook';
        $this->traitInit();
        //for now mainly used to load braintree classes
        $this->_braintreeApi = $this->getBraintreeApi();
    }     
    
    public function actionSubscriptionChargedSuccessful($id) 
    { 
        $this->triggerSubscriptionNotification(Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY, $id);
    }    
    
    public function actionSubscriptionChargedUnsuccessful($id) 
    { 
        $this->triggerSubscriptionNotification(Braintree\WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY, $id);
    }  
    
    public function actionSubscriptionCanceled($id) 
    { 
        $this->triggerSubscriptionNotification(Braintree\WebhookNotification::SUBSCRIPTION_CANCELED, $id);
    }  
    
    public function actionSubscriptionWentActive($id) 
    { 
        $this->triggerSubscriptionNotification(Braintree\WebhookNotification::SUBSCRIPTION_WENT_ACTIVE, $id);
    }    
    
    public function actionSubscriptionPastdue($id) 
    { 
        $this->triggerSubscriptionNotification(Braintree\WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE, $id);
    }    
    
    protected function triggerSubscriptionNotification($type,$subscriptionId) 
    { 
        $this->writeLog(__METHOD__.' start.. '.$type);
        $sampleNotification = Braintree\WebhookTesting::sampleNotification($type, $subscriptionId);
        $this->sendCurl(http_build_query($sampleNotification));        
        return 0;        
    }        
    
    protected function sendCurl($query)
    {
        $url = "https://".param('MERCHANT_DOMAIN')."/billings/braintree/webhook";
        $this->logInfo(__METHOD__.' url '.$url,$query);
                
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->writeLogDump(__METHOD__." cURL Error #:" , $err);
        } else {
            $this->writeLogDump(__METHOD__." cURL response" , $response);
        }           
    }
}
