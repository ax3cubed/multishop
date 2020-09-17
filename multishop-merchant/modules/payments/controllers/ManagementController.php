<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopParentController');
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController 
{
    use TransitionControllerActionTrait;
    
    public $methodFormGetAction = 'methodformget';
    public $templateGetAction = 'templateget';//use for custom method
    protected $formType = 'PaymentMethodForm';           
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        // check if module requisites exists
        $missingModules = $this->getModule()->findMissingModules();
        if ($missingModules->getCount()>0)
            user()->setFlash($this->getId(),[
                'message'=>Helper::htmlList($missingModules),
                'type'=>'notice',
                'title'=>Sii::t('sii','Missing Module'),
            ]);
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->breadcrumbsModuleName = PaymentMethod::model()->displayName(Helper::PLURAL);
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'PaymentMethod';
        $this->viewName = Sii::t('sii','Payment Methods Management');
        $this->route = 'payments/management/index';
        $this->sortAttribute = 'update_time';
        //-----------------//
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->methodFormGetAction,
            $this->templateGetAction,
        ];
        //-----------------//     
    } 
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),$this->transitionActions(false,true),[
            'view'=>[
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'name',
            ],                    
            'create'=>[
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ],
            'update'=>[
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ],                    
            'delete'=>[
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
            ],                    
        ]);
    }  
    /**
     * Get payment method form and data
     * @param type $method
     * @throws CHttpException
     */
    public function actionMethodFormGet($id)
    {
        if(isset($id)) {
            header('Content-type: application/json');
            echo CJSON::encode([
                'status'=>'success',
                'form'=>$this->getMethodFormView($id),
            ]);
            Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }        
    /**
     * Get custom payment method template data
     * @param type $method
     * @throws CHttpException
     */
    public function actionTemplateGet($method)
    {
        if(isset($method)) {
            header('Content-type: application/json');
            $templates = new CMap();
            $form = new PaymentMethodForm();
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
            foreach ($form->locales() as $locale => $localeText) {
                $templates->add($locale,[
                        'name'=>PaymentMethod::getOfflineName($method,$locale),
                        'instructions'=>OfflinePaymentForm::getInstructions($method,$locale),
                    ]);
            }
            echo CJSON::encode($templates);
            Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }       
    
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes($form->getAttributeExclusion());
            $form->loadSubFormAttributes();
        }
        else {
            $form = new PaymentMethodForm();
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
            else {
                if (isset($_GET['sid'])){
                    $shop = $this->loadModel($_GET['sid'], 'Shop');
                    $this->setSessionShop($shop);
                    $form->shop_id = $shop->id;
                }
            }
        }
        return $form;
    }  
    
    public function setFormAttributes($form,$json=false)
    {
        $form->attributes = $_POST[$this->formType];
        $form->setSubFormAttributes($_POST[get_class($form->loadSubForm())],$json);
        return $form;
    }  

    public function setModelAttributes($form)
    {
        return $form->setModelAttributes();
    }   
    
    protected function getMethodFormView($method,$model=null)
    {
        if (!isset($model))
            $form = PaymentMethodForm::createSubFormInstance($method, $this->getParentShop()->id,'create');
         else {
            $form = $model->loadSubForm();
            $form->setIsNewRecord($model->getIsNewRecord());
        }
        return Yii::app()->controller->renderPartial($form->getViewFile(),['model'=>$form],true);
    }
    
}