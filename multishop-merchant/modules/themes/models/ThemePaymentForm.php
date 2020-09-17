<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.payments.models.PaymentForm');
Yii::import('common.modules.payments.plugins.braintreeCreditCard.components.BraintreeApiTrait');
/**
 * Description of ThemePaymentForm
 *
 * @author kwlok
 */
class ThemePaymentForm extends PaymentForm
{
    use BraintreeApiTrait;
    /**
     * Fields $shop_id, $amount, $payer are requried for theme payment
     * @var type 
     */
    public $theme;//the purchase theme model
    public $payment_token;//the payment token used to pay theme
    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['shop_id, theme, payment_token', 'required'],
        ];
    }    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'payment_token'=>Sii::t('sii','Payment Token'),
        ];
    }    
    /**
     * Below info are required by payment gateway
     * @see BraintreeCreditCardTokenGateway
     */
    public function preparePayment(Theme $theme, Shop $shop)
    {
        $this->type = Payment::SALE;//must set type else payment cannot go through
        $this->status = Process::UNPAID;//must set status else payment cannot go through
        $this->method = PaymentMethod::BRAINTREE_CREDITCARD;//payment method
        $this->shop_id = $shop->id;
        $this->payer = $shop->account_id;
        $this->amount = $theme->price;
        $this->currency = $theme->currency;
        //a temp reference, limit at 20 not to exceed s_payment.reference
        //Later will be changed to receipt no when payment is confirmed
        $this->reference_no = substr($theme->id.'-'.$shop->id.time(), 0, 20);
        $this->extraPaymentData= [
            'paymentToken'=>$this->payment_token,
        ];
        $this->setBraintreeCurrency($this->currency);
        $this->paymentGatewayData = [
            'braintree'=>$this->getBraintreeConfig(),
        ];
    }     
}
