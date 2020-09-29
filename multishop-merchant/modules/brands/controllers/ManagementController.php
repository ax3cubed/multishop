<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
/**
 * Description of ManagementController for Brands
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController 
{
    use ImageControllerTrait;
    
    protected $formType = 'BrandForm';           
    
    public function init()
    {
        parent::init();
        // check if module requisites exists
        $missingModules = $this->getModule()->findMissingModules();
        if ($missingModules->getCount()>0)
            user()->setFlash($this->getId(),array('message'=>Helper::htmlList($missingModules),
                                            'type'=>'notice',
                                            'title'=>Sii::t('sii','Missing Module')));  
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::BRAND_IMAGE; 
        $this->setSessionActionsExclude();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = Brand::model()->displayName(Helper::PLURAL);        
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Brand';
        $this->viewName = Sii::t('sii','Brands Management');
        $this->route = 'brands/management/index';
        $this->sortAttribute = 'update_time';
        //-----------------//
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            'ckeditorimageupload',
            $this->imageUploadAction,
        ]);
        //-----------------
    }
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @see ImageControllerTrait::runBeforeAction()
     */
    protected function beforeAction($action)
    {
        return $this->runBeforeAction($action);
    } 
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),$this->imageActions(),array(
            'view'=>array(
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'name',
            ),                    
            'create'=>array(
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
                'setModelAttributesMethod'=>'setModelAttributes',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'setModelAttributesMethod'=>'setModelAttributes',
            ),
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
            ),
        ));
    }  
    
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id, 'update');
            $form->loadLocaleAttributes();
        }
        else {
            $form = new $this->formType(Helper::NULL,Brand::model()->getCreateScenario());
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
        }
        return $form;
    }

    public function setModelAttributes($form)
    {
        //[1]copy form attributes to model attributes
        $form->modelInstance->attributes = $form->getAttributes();
        //[2]set model scenario to follow $form scenario
        $form->modelInstance->setScenario($form->getScenario());
        return $form;
    }

    protected function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Products
        $sections->add(array('id'=>'products',
                             'name'=>Sii::t('sii','Products').($model->hasProducts()?' ('.$model->productCount.')':''),
                             'heading'=>true,'top'=>true,
                             'html'=>$this->widget($this->module->getClass('listview'), 
                                        array(
                                            'dataProvider'=> new CArrayDataProvider($model->products),
                                            'template'=>'{items}',
                                            'emptyText'=>'',
                                            'itemView'=>'_product',
                                        ),true)
                    ));
        return $sections->toArray();
    }          
}
