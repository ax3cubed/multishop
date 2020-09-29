<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.payments.plugins.braintreeCreditCard.components.BraintreeApiTrait');
/**
 * Description of PaymentController
 *
 * @author kwlok
 */
class PaymentController extends BillingBaseController 
{
    use BraintreeApiTrait;
    /**
     * Index page
     */
    public function actionIndex()
    {
        $this->pageTitle = Sii::t('sii','Payment Cards');
        $this->render('index',['dataProvider'=>$this->findPaymentCards(user()->getId())]);
    }
    /**
     * Change payment card
     */
    public function actionChange()
    {
        $this->pageTitle = Sii::t('sii','Change Payment Method');

        if (!isset($_POST['payment_data'])){
            throwError404('Payment not found');
        }        
        
        $paymentData = $this->decodePaymentCard($_POST['payment_data']);
        
        if (isset($_POST['payment_method_nonce'])){
            
            $response = $this->module->braintreeApi->updatePaymentMethod($paymentData['token'],$_POST['payment_method_nonce']);
            if ($response['success']){
                logInfo(__METHOD__.' credit card updated ok');
                user()->setFlash($this->model,[
                    'message'=>Sii::t('sii','Payment card is changed successfully'),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Payment Card Update'),
                ]);
                $this->redirect(url('billing/payment'));
                Yii::app()->end();
            }
            else {
                logError(__METHOD__.' payment method error',$response);
                user()->setFlash($this->model,[
                    'message'=>$response['response']->message,
                    'type'=>'error',
                    'title'=>Sii::t('sii','Payment Card Update'),
                ]);
            }            
            unset($_POST);
        }
             
        $this->render('change',['data'=>$paymentData]);
    }    
    /**
     * Create payment card
     */
    public function actionCreate()
    {
        $this->pageTitle = Sii::t('sii','Create Payment Method');

        if (isset($_POST['payment_method_nonce'])){
            
            $response = $this->module->braintreeApi->createPaymentMethod(user()->getId(),$_POST['payment_method_nonce']);
            if ($response['success']){
                logInfo(__METHOD__.' credit card created ok');
                user()->setFlash($this->model,[
                    'message'=>Sii::t('sii','Payment card is created successfully'),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Payment Card Create'),
                ]);
                $this->redirect(url('billing/payment'));
                Yii::app()->end();
            }
            else {
                logError(__METHOD__.' payment method error',$response);
                user()->setFlash($this->model,[
                    'message'=>$response['response']->message,
                    'type'=>'error',
                    'title'=>Sii::t('sii','Payment Card Create'),
                ]);
            }            
            unset($_POST);
        }
             
        $this->render('create');
    }

    public function getCardQuickViewColumn($data)
    {
        return [
            ['label'=>Sii::t('sii','Credit Card Number'),'type'=>'raw','value'=> '<i class="fa fa-credit-card"></i> '.$data['cardType'].' ('.$data['maskedNumber'].')'],
            ['label'=>Sii::t('sii','Expiration'),'type'=>'raw','value'=> $data['expirationDate']],
        ];
    }
           
}
