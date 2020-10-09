<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopManagementControllerTrait');
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController 
{
    use ShopManagementControllerTrait, ImageControllerTrait, TransitionControllerActionTrait;
    
    protected $formType = 'ShopForm';           
    protected $formAddressType = 'ShopAddressForm';           

    public function init()
    {
        parent::init();
        $this->getModule()->registerScripts();
        // check if module requisites exists
        $missingModules = $this->getModule()->findMissingModules();
        if ($missingModules->getCount()>0)
            user()->setFlash($this->getId(),[
                'message'=>Helper::htmlList($missingModules),
                'type'=>'notice',
                'title'=>Sii::t('sii','Missing Module'),
            ]);  
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::SHOP_IMAGE; 
        $this->setSessionActionsExclude();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Shop';
        $this->viewName = Sii::t('sii','Shops Management');
        $this->route = 'shops/management/index';
        $this->sortAttribute = 'update_time';
        $this->searchMap = [
            'shop' => 'name',
            'date' => 'create_time',
            'timezone' => 'timezone',
            'currency' => 'currency',
            'shipping' => 'id',//attribute "id" is used as proxy to search into shippings
            'payment_method' => 'slug',//attribute "slug" is used as proxy to search into payment methods
            'status'=>'status',
        ];
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'ShopFilterForm';
        $this->filterFormHomeUrl = url('shops/management');
        $this->filterFormQuickMenu = [
            //['id'=>'apply','title'=>Sii::t('sii','Apply Shop'),'subscript'=>Sii::t('sii','apply'), 'url'=>['apply'],'linkOptions'=>['class'=>'primary-button']],    
            ['id'=>'create','title'=>Sii::t('sii','Create Shop'),'subscript'=>Sii::t('sii','create'), 'url'=>['create'],'linkOptions'=>[]],    
        ];
        //-----------------
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            'imageget',
            'goto',
            'stateget',
        ]);
        //-----------------//
    }  
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @see ImageControllerTrait::runBeforeAction()
     */
    protected function beforeAction($action)
    {
        if ($this->hasSessionShop() && !$this->hasParentShop() && $action->id!='index')
            $this->loadSessionParentShop();//for index, parent shop must be null else loading index will hit error
        return $this->runBeforeAction($action);
    } 
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),$this->imageActions(),$this->transitionActions(),[
            'submitApplication'=>[
                'class'=>'common.components.actions.api.ApiCreateAction',
                'apiRoute'=>'/shops',
                'model'=>'ShopApplicationForm',
                'queryParams'=>'/apply',
                'viewFile'=>'apply',
                'beforeRender'=>'prepareApplicationData',
                'flashIdOnSuccess'=>'Shop',
                'flashTitle'=>Sii::t('sii','{object} Application',['{object}'=>Shop::model()->displayName()]),
                'flashMessage'=>Sii::t('sii','Thanks for your application! We will process your application and notify you the result shortly'),
            ],
            'view'=>[
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'name',
                'beforeRender'=>'setSessionVariables',
            ],              
            'update'=>[
                'class'=>'common.components.actions.UpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ],                   
            'imageget'=>[
                'class'=>'common.components.actions.ImageGetAction',
            ],
            'stateget'=>[
                'class'=>'common.components.actions.AddressStateGetAction',
            ],       
        ]);
    }
    /**
     * Url Redirectiion with shop id stored in session
     */
    public function actionGoto()
    {
        if (!isset($_GET['view']))
            throwError400(Sii::t('sii','View route not found'));
        if (!isset($_GET['shop']))
            throwError400(Sii::t('sii','Shop not found'));
                        
        $model = Shop::model()->mine()->findByAttributes(['slug'=>$_GET['shop']]);  
        $this->setSessionShop($model);
        $route = $_GET['view'];
        if (isset($_GET['subview']))
            $route .= '/'.$_GET['subview'];
            
        $this->redirect(url($route));
    }
    /**
     * Start first shop configuration
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionStart()
    {
        $this->_createInternal('startFirstShop', Sii::t('sii','My First Shop'));
    }
    /**
     * Create a new shop
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->_createInternal('create', Sii::t('sii','Create Shop'));
    }
    /**
     * Apply a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionApply()
    {
        $this->pageTitle = Sii::t('sii','Apply Shop').' | '.Yii::app()->name;
        $model = $this->prepareApplicationData(new ShopApplicationForm());
        $this->render('apply',array('model'=>$model));
    }
    /**
     * Prepare model data before render
     * @param type $model
     * @return type
     */
    public function prepareApplicationData($model)
    {
        $model->applyUrl = url('shops/management/submitApplication');
        $model->formView = '_form_apply';
        return $model;
    }
    
    public function setSessionVariables($model)
    {
        $this->setSessionShop($model);
        $this->setSessionProduct(null);
        $this->setPageTitle($model->parseName(user()->getLocale()));
    }
    
    public function prepareForm($id)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes();
            $form->loadAddressFormAttributes();
            $this->setSessionVariables($form->modelInstance);
        }
        //else {create action - leave empty}
        return $form;         
    }
    /**
     * Set form attributes
     * @param type $form 
     * @param type $json indicate if to assign by json value
     * @return type
     */
    public function setFormAttributes($form,$json=false)
    {
        $form->assignLocaleAttributes($_POST[$this->formType],$json);
        if (isset($_POST[$this->formAddressType])){
            $form->setAddressFormAttributes($_POST[$this->formAddressType],$json);
        }
        return $form;
    }    
    
    public function setModelAttributes($form)
    {
        return $form->setModelAttributes();
    }   
    
    public function getApplySectionsData($model) 
    {
        $sections = new CList();
        //section 1: Shop
        $sections->add([
            'id'=>'setting',
            'name'=>Sii::t('sii','Shop Information'),
            'heading'=>true,'top'=>true,
            'viewFile'=>$this->module->getView('shops.shopsettingform'),
            'viewData'=>['model'=>$model],
        ]);
        //section 2: Contact
        $sections->add([
            'id'=>'contact',
            'name'=>Sii::t('sii','Contact Information'),
            'heading'=>true,
            'viewFile'=>$this->module->getView('shops.shopcontactform'),
            'viewData'=>['model'=>$model],
        ]);
        //section 3: Locale
        $sections->add([
            'id'=>'locale',
            'name'=>Sii::t('sii','Locale Information'),
            'heading'=>true,
            'viewFile'=>$this->module->getView('shops.shoplocaleform'),
            'viewData'=>['model'=>$model],
        ]);
        return $sections->toArray();
    }          
    
    public function showSidebar()
    {
        return user()->hasRole(Role::MERCHANT);
    }     
    /**
     * Internal logic to handle shop creation
     * @param type $service
     * @param type $pageTilte
     */
    private function _createInternal($service,$pageTilte)
    {
        $this->pageTitle = $pageTilte.' | '.Yii::app()->name;
        try {
            $model = $this->module->serviceManager->{$service}(user()->getId());
            $this->redirect($model->getViewUrl());
            Yii::app()->end();
        } catch (CException $ex) {
            logError(__METHOD__,$ex->getTraceAsString());
            user()->setFlash($this->modelType,[
                'message'=>$ex->getMessage(),
                'type'=>'error',
                'title'=>Sii::t('sii','Shop Error'),
            ]);
            $this->redirect(url('shop'));
        }
    }
}