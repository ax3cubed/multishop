<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.accounts.controllers.SignupController');
Yii::import('application.models.SignupMerchantForm');
/**
 * Description of RegisterController
 * This controller extends directly from SignupController with customized signup action
 * 
 * @author kwlok
 */
class RegisterController extends SignupController 
{
    /**
     * Set this property so that can re-use the code inside parent controller
     * @var CModule
     */
    public $module;
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        $this->module = Yii::app()->getModule('accounts');
    }
    /**
     * Create account using quickform 
     */
    public function actionIndex() 
    {
        $this->registerFormCssFile();
        $this->registerCssFile('application.assets.css','application.css');
        $this->registerCssFile('wcm.assets.css','wcm.css');
       
        $form = new SignupMerchantForm();

        if (isset($_POST['ajax']) && $_POST['ajax']==='signup-merchant-form') {
            echo CActiveForm::validate($form,['name','email','password']);
            Yii::app()->end();
        }

        if (isset($_POST['SignupMerchantForm'])){
            try {
                $form->attributes = $_POST['SignupMerchantForm'];
                $this->module->serviceManager->signup($form);
                header('Content-type: application/json');
                $this->redirect(url('account/signup/complete',['email'=>$form->email]));
                unset($_POST);
                Yii::app()->end();
                
            } catch (CException $e) {
                logError(__METHOD__.' '.$e->getMessage(),[],false);
                $form->unsetAttributes(['password']);
                user()->setFlash(get_class($form),[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>null,
                ]);
            }            
        }

        if ($this->exceedCapacity())
            $this->renderView('common.modules.accounts.views.signup.hold');
        else {
            //$form->title = Sii::t('sii','Start your {n} days free trial',['{n}'=>$form->trialDuration]);
            $form->title = Sii::t('sii','Sign up');
            $this->render('index',['model'=>$form]);
        }
    }

}
