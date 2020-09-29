<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('console.components.CommandLogFileTrait');
Yii::import('common.modules.payments.plugins.braintreeCreditCard.components.BraintreeApiTrait');
Yii::import('common.modules.plans.models.*');
Yii::import('common.modules.billings.models.*');
/**
 * Description of BraintreeApiCommand
 *
 * @author kwlok
 */
class BraintreeApiCommand extends SCommand 
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
        $this->logFileName = 'braintree_api';
        $this->traitInit();
        //for now mainly used to load braintree classes
        $this->_braintreeApi = $this->getBraintreeApi();
    }     
    
    public function actionSubscribeTrial($paymentMethodToken,$plan,$price,$duration=0)
    {
        $this->writeLogDump(__METHOD__.' start..');
        //this test alters plan with n day trial
        $result = $this->_braintreeApi->createSubscription($paymentMethodToken, $plan, [
            'price' => $price,
            'trialPeriod' => true,
            'trialDuration' => $duration,
            'trialDurationUnit' => 'day'
        ]);
        $this->writeLogDump(__METHOD__.' success '.$result['success'],$result['response']);
        return 0;
    }
}
