<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('products.controllers.ProductBaseController');
/**
 * Description of CategoryController
 *
 * @author kwlok
 */
class CategoryController extends ProductBaseController 
{
    public    $subcategoryFormGetAction = 'subcategoryformget';
    public    $subcategoryFormDelAction = 'subcategoryformdel';
    protected $stateVariable = SActiveSession::CATEGORY_SUB;
    protected $formType = 'CategoryForm';           
    protected $formChildType = 'CategorySubForm';

    public function init()
    {
        parent::init();
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::CATEGORY_IMAGE; 
        $this->setSessionActionsExclude([
            $this->subcategoryFormGetAction,
            $this->subcategoryFormDelAction,
        ]);
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = false;
        $this->showBreadcrumbsController = true;  
        $this->breadcrumbsControllerName = Category::model()->displayName(Helper::PLURAL);
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Category';
        $this->viewName = Sii::t('sii','Categories Management');
        $this->route = 'products/category/index';
        $this->sortAttribute = 'update_time';
        //-----------------
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            $this->subcategoryFormGetAction,
            $this->subcategoryFormDelAction,
        ]);
        //-----------------//
    }        
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),$this->imageActions(),[
            'view'=>[
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'beforeRender'=>'setSessionParentShop',
                'pageTitleAttribute'=>'name',
            ],                   
            'create'=>[
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
                'service'=>'create'.$this->modelType,
            ],
            'update'=>[
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
                'service'=>'update'.$this->modelType,
            ],            
            'delete'=>[
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
                'service'=>'delete'.$this->modelType,
            ],                    
            $this->subcategoryFormGetAction=>[
                'class'=>'common.widgets.schildform.actions.ChildFormGetAction',
                'stateVariable'=>$this->stateVariable,
                'formKeyStateVariable'=>$this->shopStateVariable,
                'formModel'=>$this->formChildType,
                'mandatoryGetParam'=>'cid',
                'mandatoryGetParamAttributeMapping'=>'category_id',
            ],      
            $this->subcategoryFormDelAction=>[
                'class'=>'common.widgets.schildform.actions.ChildFormDelAction',
                'stateVariable'=>$this->stateVariable,
                'beforeDelete'=>'beforeDeleteChildModel',
            ],
        ]);
    }  
    
    public function beforeDeleteChildModel($key)
    {
        $model = $this->loadModel($key, 'CategorySub', false);
        if ($model!=null){
            $model->setScenario('delete');
            //logTrace(__METHOD__,$model->attributes);
            if (!$model->validate()){
                return ['status'=>'failure','key'=>$key,'message'=>$this->getFlashAsString('error',$model->getError('id'),Sii::t('sii','Subcategory Delete Error'))];
            }
        }
        return ['status'=>'success'];
    }
    
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id, Category::model()->getSkipSlugScenario());
            $form->loadLocaleAttributes();
            $form->subcategories = $form->getChildForms();
            SActiveSession::load($this->stateVariable, $form, 'subcategories');
        }
        else {
            $form = new $this->formType(Helper::NULL,Category::model()->getCreateScenario());
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
        }
        return $form;
    }
    
    public function setFormAttributes($form,$json=false)
    {
        $form->assignLocaleAttributes($_POST[$this->formType],$json);
        if (isset($_POST[$this->formChildType])){
            SActiveSession::clear($this->stateVariable);//we will use newly submitted $_POST[$this->formChildType] as new base
            foreach ($_POST[$this->formChildType] as $data) {//scan through all options
                //[1]reinstantiate child form
                $childForm = $form->instantiateChildForm($data,'category_id');
                //[2]setup new session options based on newly submitted $_POST[$this->formChildType]
                SActiveSession::add($this->stateVariable, $childForm);
            }//end for loop
            $form->subcategories = SActiveSession::load($this->stateVariable);
        }
        else {
            $form->subcategories = [];            
        }
        return $form;
    }    
    
    public function setModelAttributes($form)
    {
        //[1]copy form attributes to model attributes
        $form->modelInstance->attributes = $form->getModelAttributes();
        //[2]set model scenario to follow $form scenario
        $form->modelInstance->setScenario($form->getScenario());
        //[3]copy form options attributes to model options attributes
        $form->modelInstance->subcategories = $form->getChildModels();
        return $form;
    }    
    
    protected function getSectionsData($model,$locale) 
    {
        $sections = new CList();
        //section 1: Products
        $sections->add([
            'id'=>'products',
            'name'=>$model->displayLanguageValue('name',$locale).($model->hasProducts()?' ('.$model->productCount.')':''),
            'heading'=>true,
            'top'=>true,
            'html'=>$model->hasProducts()  ? $this->widget($this->module->getClass('listview'),[
                                'dataProvider'=> new CArrayDataProvider($model->products),
                                'template'=>'{items}',
                                'emptyText'=>'',
                                'itemView'=>$this->module->getView('brands.productthumbnail'),
                            ],true) 
                        : Sii::t('sii','This {category} has no products.',['{category}'=>$model->displayName()])
                    ]);
        foreach ($model->subcategories as $subcategory) {
            //section n: Subcategory products
            $sections->add([
                'id'=>'products',
                'name'=>$subcategory->displayLanguageValue('name',$locale).($subcategory->hasProducts()?' ('.$subcategory->productCount.')':''),
                'heading'=>true,
                'html'=>$subcategory->hasProducts() ? $this->widget($this->module->getClass('listview'),[
                                    'dataProvider'=> new CArrayDataProvider($subcategory->products),
                                    'template'=>'{items}',
                                    'emptyText'=>'',
                                    'itemView'=>$this->module->getView('brands.productthumbnail'),
                                ],true)
                            : Sii::t('sii','This {category} has no products.',array('{category}'=>$subcategory->displayName()))
                        ]);

        }
        return $sections->toArray();
    }    
    
    protected function loadChildFormWidget($category_id)
    {
        $runScript = <<<EOJS
$(document).ready(function(){
    $('.add-button').click(function(){
        getsubcategory($category_id);
    });
});
EOJS;
        $this->widget('common.widgets.schildform.SChildForm', [
            'stateVariable' => $this->stateVariable,
            'deleteControl' => 'all',
            'headerData'=>[
                CategorySub::model()->getAttributeLabel('name'),
                CategorySub::model()->getAttributeLabel('slug'),
            ],
            'runScript' => $runScript,
        ]);        
    }    
      
}