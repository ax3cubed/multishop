<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.plans.components.SubscriptionFlashTrait');
Yii::import('common.modules.plans.actions.ApiSubscribeAction');
Yii::import('common.modules.plans.actions.ApiUnsubscribeAction');
Yii::import("plans.controllers.SubscriptionControllerTrait");
/**
 * Description of SubscriptionController
 *
 * @author kwlok
 */
class SubscriptionController extends AuthenticatedController 
{  
    use SubscriptionControllerTrait, SubscriptionFlashTrait;
    
    private $_subscription;
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        $this->registerCommonFiles();
        $this->registerFormCssFile();
        $this->registerJui();
        $this->registerChosen();
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            'index',
            'getplans',
        ];
        //-----------------//
    } 
    /**
     * IMPORTANT NOTE:
     * This controller is not including 'subscription' filter as 'SubscriptionFilter' are redirecting back to this controller when access if not granted.
     * Else will hit error: ERR_TOO_MANY_REDIRECTS
     * @return array action filters
     */
    public function filters()
    {
        $filters = parent::filters();
        foreach ($filters as $key => $value) {
            if ($value=='subscription')
                unset($filters[$key]);
        }
        
        logTrace(__METHOD__.' ',$filters);
        return $filters;        
    }    
    /**
     * Display approved plans
     */
    public function actionIndex()
    {
        if (user()->isGuest){
            //the return url is set at SubscriptionFilter
            $this->redirect(Yii::app()->user->loginUrl[0].'?returnUrl='.CHtml::encode(Yii::app()->user->returnUrl));
            Yii::app()->end();
        }
        elseif (!user()->isActivated){//user is registered only - normally come from social network
            $this->module->runControllerMethod('accounts/welcome','renderActivatePreSignup');
            Yii::app()->end();
        }
        else {
            /**
             * @var $shop To identify which shop the subscription is for; If value is null, it is a new shop subcription.
             * If value is with value and a valid shop id, this will be a subscription review / upgrade / downgrade
             */
            $shop = isset($_GET['shop'])?$_GET['shop']:null;
            if (isset($shop) && strlen($shop)>0){
                $subscription = user()->getOnlineSubscription($shop);
                $this->setChangePlanFlash($subscription, 'Plan');
            }
            $this->render('index',['dataProvider'=>$this->dataProvider,'shop'=>$shop]);
        }
    }
    /**
     * Get all plans based on package
     */
    public function actionGetPlans($package)
    {
        $model = new SubscriptionForm($this->dataProvider);
        $model->loadPackage($package);
        $model->createPlanData();
        echo CJSON::encode(['plans'=>$model->plans,'buttonCaption'=>$this->getCheckoutButtonCaption($model)]);
        Yii::app()->end();
    }    
    /**
     * Subcription purchase
     * Step 1: Checkout a plan
     */
    public function actionCheckout()
    {
        $form = new SubscriptionForm($this->dataProvider);
        if (isset($_POST['Subscription']['shop_id'])){
            $form->shop_id = $_POST['Subscription']['shop_id'];
        }
        
        if (isset($_POST['Package']['id'])){
            try {
                $form->loadPackage($_POST['Package']['id']);
                
            } catch (Exception $ex) {
                logError(__METHOD__.' error',$ex->getTraceAsString());
                user()->setFlash('checkout',[
                    'message'=>$ex->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Checkout Error'),
                ]);
            }
        }
        
        if (!$form->hasPackage)
            $form->loadPackage($form->defaultPackage);//load default first package
            
        if ($form->hasPlans){
            $form->createPlanData();
            $this->render('checkout',['model'=>$form]);
        }
        else
            $this->render('empty');
    }
    /**
     * Subcription purchase
     * Step 2: Add billing information for a subscribed plan
     */
    public function actionBilling()
    {
        if (isset($_POST['SubscriptionForm'])){
            $form = new SubscriptionForm($this->dataProvider);
            $form->attributes = $_POST['SubscriptionForm'];
            $form->createPlanData();
            $form->loadDefaultPaymentToken();
            
            if (!$form->validate()){
                user()->setFlash('checkout',[
                    'message'=>Helper::htmlErrors($form->errors),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Subscription Error'),
                ]);
                $this->redirect('checkout');
                Yii::app()->end();
            }
            unset($_POST);
            $this->render('billing',['model'=>$form]);
            Yii::app()->end();
        }
        throwError404(Sii::t('sii','Page not found'));
    }
    /**
     * Subcription purchase
     * Step 3: Complete subscription purchase
     */
    public function actionComplete()
    {
        if (isset($_POST['SubscriptionForm'])){
            
            try {
                //set scenario to 'payment' to have payment data validation
                $form = new SubscriptionForm($this->dataProvider,null,SubscriptionForm::SCENARIO_PAYMENT);
                $form->attributes = $_POST['SubscriptionForm'];
                $form->createPlanData();
                $form->createPaymentNonce($_POST);//if any
                if (!$form->validate()){
                    throw new CException(Helper::htmlErrors($form->errors));
                }
                
                unset($_POST);
                
                $action = new ApiSubscribeAction($this,__METHOD__,$form->plan);
                $action->postFields = json_encode([
                    'shop'=>$form->shop_id,
                    'package'=>$form->package,
                    'paymentToken'=>$form->payment_token,
                    'paymentNonce'=>$form->paymentNonce,
                ]);
                $action->afterSubscribe = 'returnSubscription';
                $this->runAction($action);  
                $this->render('complete',['model'=>$this->_subscription,'newSubscription'=>isset($form->shop_id)?false:true]);
                Yii::app()->end();
                
            } catch (CException $ex) {
                logError(__METHOD__.' error',$ex->getTraceAsString());
                user()->setFlash('checkout',[
                    'message'=>$ex->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Subscription Error'),
                ]);
                $this->redirect('checkout');
                Yii::app()->end();
            }
        }
        throwError404(Sii::t('sii','Page not found'));
    } 
    /**
     * Retry charging a subscription
     */
    public function actionRetryCharge($shop)
    {
        if (isset($_POST['SubscriptionRetryChargeForm']['subscription_no']) && 
            isset($_POST['SubscriptionRetryChargeForm']['amount'])){

            $subscription = user()->getOnlineSubscription($shop);
            
            $form = new SubscriptionRetryChargeForm();
            $form->subscription_no = $_POST['SubscriptionRetryChargeForm']['subscription_no'];
            $form->amount = $_POST['SubscriptionRetryChargeForm']['amount'];
            
            try {
                
                Yii::app()->getModule("billings")->serviceManager->repaySubscription($form);
                unset($_POST);
                user()->setFlash(get_class($subscription),[
                    'message'=>null,
                    'type'=>'success',
                    'title'=>Sii::t('sii','Subsription "{id}" is charged {amount} successfully',['{id}'=>$form->subscription_no,'{amount}'=>$subscription->plan->formatCurrency($form->amount,$subscription->plan->currency)]),
                ]);
                $this->redirect($subscription->viewUrl);
                Yii::app()->end();
                
            } catch (Exception $ex) {
                user()->setFlash('Pastdue',[
                    'message'=>$ex->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Payment Error'),
                ]);
                $this->render('pastdue',['form'=>$form,'model'=>$subscription]);
                Yii::app()->end();
            }
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }
    /**
     * Cancel subscription
     */
    public function actionCancel($shop)
    {
        $subscription = user()->getOnlineSubscription($shop);
        if ($subscription!=null){
            $this->setCancelFlash($subscription,$this->action->id);
            if (isset($_POST['Subscription']['package_id']) && isset($_POST['Subscription']['subscription_no']) && $subscription->subscription_no==$_POST['Subscription']['subscription_no']){
                $action = new ApiUnsubscribeAction($this,__METHOD__,$subscription->subscription_no,$shop,$_POST['Subscription']['package_id']);
                $action->afterUnsubscribe = 'cancelSuccessful';
                $this->runAction($action);  
                Yii::app()->end();
            }

            $this->render('cancel',['model'=>$subscription]);
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }    
    /**
     * Display pending subscription
     */
    public function actionPending($shop)
    {
        $subscription = user()->getPendingSubscription($shop);
        if ($subscription!=null){
            $this->setPendingFlash($subscription,$this->action->id);
            $this->render('pending',['model'=>$subscription]);
        }
        else
            throwError403(Sii::t('sii','Unauthorized Access'));
    }    
    /**
     * Display pastdue subscription
     */
    public function actionPastdue($shop)
    {
        $subscription = user()->getPastdueSubscription($shop);
        if ($subscription!=null){
            $this->clearGlobalFlash();//clear global flash so that only show pastdue flash specific to this shop
            $this->setPastdueFlash($this,$subscription);
            $form = new SubscriptionRetryChargeForm();
            $form->subscription_no = $subscription->subscription_no;
            $form->amount = $subscription->plan->price;
            $this->render('pastdue',['form'=>$form,'model'=>$subscription]);
        }
        else
            throwError403(Sii::t('sii','Unauthorized Access'));
    }    
    /**
     * Display suspended subscription
     */
    public function actionSuspend($shop)
    {
        $subscription = user()->getSuspendedSubscription($shop);
        if ($subscription!=null){
            $this->setSuspendFlash($subscription,$this->action->id);
            $this->render('suspend',['model'=>$subscription]);
        }
        else
            throwError403(Sii::t('sii','Unauthorized Access'));
    }    
    /**
     * Get all the approved packages
     * @return type
     */
    public function getDataProvider($removeFreeTrial=false)
    {
        return $this->getPublishedPackages($removeFreeTrial);
    }
    /**
     * Gather the successful subscription returned from api 
     */
    public function returnSubscription($subscription)
    {
        logInfo(__METHOD__.' data',$subscription);
        $model = Subscription::model()->findByPk($subscription['id']);
        if ($model!=null){
            $this->_subscription = $model;
        }
        else
            throw new CException('Subscription id '.$subscription['id'].' not found');
    }    
    /**
     * Gather the successful cancellation returned from api 
     */
    public function cancelSuccessful($params)
    {
        user()->setFlash('Shop',[
            'message'=>null,
            'type'=>'success',
            'title'=>Sii::t('sii','Your {package} subscription "{subscription_no}" for shop "{shop}" is cancelled successfully.',[
                            '{package}'=>Package::siiName($params['package_id']),
                            '{subscription_no}'=>$params['subscription_no'],
                            '{shop}'=>$params['shop_id'],
                        ]),
        ]);
        $this->redirect(url('shops'));
    }    
    /**
     * Display proper checkout button caption based on form data
     * @param type $form
     * @return string
     */
    public function getCheckoutButtonCaption($form)
    {
        return $form->requiresPayment?Sii::t('sii','Proceed Payment'):Sii::t('sii','Proceed');
    }  
    
    public function showCreditCardForm($model)
    {
        return $model->requiresPayment && $model->payment_token==null;
    }
}
