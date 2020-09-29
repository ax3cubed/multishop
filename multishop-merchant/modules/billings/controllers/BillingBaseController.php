<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of BillingBaseController
 *
 * @author kwlok
 */
class BillingBaseController extends AuthenticatedController 
{
    use BillingControllerTrait;
    
    public function init()
    {
        parent::init();
        $this->modelType = 'Billing';
    }    
    
    public function getPlanOverviewContent($subscription,$fields=[])
    {
        //not specify all fields will be shown
        if (empty($fields)){
            $fields = [
                'plan_id','subscription_no','start_date','end_date','renewal','charged','payment','billing',
            ];
        }
        if (isset($subscription)){
            $info = [
                ['label'=>$subscription->getAttributeLabel('shop_id'),'type'=>'raw','value'=>CHtml::link($subscription->shop->parseName(user()->getLocale()),$subscription->shop->viewUrl),'visible'=>$subscription->isActive],
            ];
            if (in_array('plan_id',$fields))
                $info[] = ['label'=>$subscription->getAttributeLabel('plan_id'),'value'=>$subscription->name];
            if (in_array('subscription_no',$fields))
                $info[] = ['label'=>$subscription->getAttributeLabel('subscription_no'),'type'=>'raw','value'=>CHtml::link($subscription->subscription_no,$subscription->viewUrl)];
            if (in_array('start_date',$fields))
                $info[] = ['label'=>$subscription->getAttributeLabel('start_date'),'value'=>$subscription->start_date];
            if (in_array('end_date',$fields)){
                $info[] = ['label'=>$subscription->getAttributeLabel('end_date'),'value'=>Sii::t('sii','Never'),'visible'=>$subscription->plan->isFree&&!$subscription->plan->isTrial];
                $info[] = ['label'=>$subscription->getAttributeLabel('end_date'),'value'=>$subscription->end_date,'visible'=>$subscription->plan->isRecurringCharge||$subscription->plan->isTrial];
            }
            if (in_array('renewal',$fields))
                $info[] = ['label'=>Sii::t('sii','Renewal'),'value'=>$subscription->plan->recurringDesc,'visible'=>$subscription->plan->isRecurringCharge];
            if (in_array('charged',$fields))
                $info[] = ['label'=>$subscription->getAttributeLabel('charged'),'value'=>Helper::htmlColorText($subscription->getChargedStatusText(),false),'type'=>'raw'];

            $paymentFields = ['payment','billing'];
            foreach ($paymentFields as $key => $field) {
                if (!in_array($field,$fields))
                    unset($paymentFields[$key]);
            }            
            
            return array_merge($info, $this->findSubscriptionPaymentMethod($subscription,$paymentFields));
        }
        else {
            return [
                ['label'=>Sii::t('sii','Plan'),'type'=>'raw','value'=>Sii::t('sii','You have not subscribed to any plan yet.')],
            ];
        }
    }
    /**
     * Find subscription payment card info (make public so that other controller can access)
     * @param type $subscription
     * @param type $fields
     * @return string
     */
    public function findSubscriptionPaymentMethod($subscription,$fields=['payment','billing'])
    {
        $content = [];
        if (in_array('payment',$fields)||in_array('billing',$fields)){
            if (($response = $this->module->braintreeApi->findSubscription($subscription->subscription_no)) != false){
                if (in_array('payment',$fields)){
                    foreach ($response->transactions as $transaction) {
                        if ($transaction->creditCard['token']==$subscription->payment_token){
                            $cardInfo = '<i class="fa fa-credit-card"></i> '.$transaction->creditCardDetails->cardType.' ('.$transaction->creditCardDetails->maskedNumber.'), '.Sii::t('sii','Expiration: {date}',['{date}'=>$transaction->creditCardDetails->expirationDate]);
                            $content[] = ['label'=>Sii::t('sii','Payment Method'),'type'=>'raw','value'=> $cardInfo];
                            break;//only need one card info as rep
                        }
                    }
                }
                if (in_array('billing',$fields)){
                    $content[] = ['label'=>Sii::t('sii','Next Bill Amount'),'type'=>'raw','value'=> $subscription->plan->currency.' '.$response->nextBillAmount];
                    $content[] = ['label'=>Sii::t('sii','Next Bill Date'),'type'=>'raw','value'=> $response->nextBillingDate->format('Y-m-d')];
                }
            }
        }
        return $content;
    }
    
    protected function findPaymentCards($customerId)
    {
        $cards = [];
        if (($response = $this->module->braintreeApi->findCustomer($customerId)) != false){
            foreach ($response->paymentMethods as $paymentMethod) {
                $data = [
                    'token'=>$paymentMethod->token,
                    'cardType'=>$paymentMethod->cardType,
                    'expirationDate'=>$paymentMethod->expirationDate,
                    'maskedNumber'=>$paymentMethod->maskedNumber,
                ];
                $cards[] = array_merge($data,[
                    'payload'=> $this->encodePaymentCard($data),
                    'id'=>$paymentMethod->uniqueNumberIdentifier,
                ]);//include encoded payload itself
            }
        }
        return new CArrayDataProvider($cards,['keyField'=>false]);
    }  
    
    protected function encodePaymentCard($cardData) 
    {
        return base64_encode(json_encode($cardData));
    }

    protected function decodePaymentCard($cardData) 
    {
        return json_decode(base64_decode($cardData),true);
    }    
    /**
     * Construct customer payment cards for selection (make public so that other controller can access)
     * @param type $subscription
     * @param type $fields
     * @return string
     */
    public function getPaymentCardTemplates()
    {
        $templates = new CMap();
        foreach ($this->findPaymentCards(user()->getId())->rawData as $data) {
            $content = '<i class="fa fa-credit-card"></i> '.$data['cardType'].' ('.$data['maskedNumber'].'), '.Sii::t('sii','Expiration: {date}',['{date}'=>$data['expirationDate']]);
            $templates->add($data['token'],CHtml::tag('span',[],$content));
        }
        return $templates->toArray();
    }
        
}
