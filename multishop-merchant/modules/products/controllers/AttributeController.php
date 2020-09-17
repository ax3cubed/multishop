<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('products.controllers.ProductBaseController');
/**
 * Description of AttributeController
 *
 * @author kwlok
 */
class AttributeController extends ProductBaseController 
{
    public    $optionFormGetAction = 'optionformget';
    public    $optionFormDelAction = 'optionformdel';
    public    $attrFormGetAction = 'attrformget';
    protected $formType = 'ProductAttributeForm';
    protected $formOptionType = 'ProductAttributeOptionForm';
    protected $stateVariable = SActiveSession::PRODUCT_ATTRIBUTE;
    
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Product Attribute Management');
        $this->sessionActionsExclude = array($this->optionFormGetAction,$this->optionFormDelAction);
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->breadcrumbsModuleName = Sii::t('sii','Attributes');
        $this->showBreadcrumbsModule = true;
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentProduct();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'ProductAttribute';
        $this->route = 'product/attribute';
        $this->defaultScope = 'locateProduct';
        $this->pageViewOption = SPageIndex::VIEW_GRID;
        $this->enableViewOptions = false;
        $this->enableSearch = false;
        $this->sortAttribute = 'update_time';
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->optionFormGetAction,
            $this->optionFormDelAction,
            $this->attrFormGetAction,
        ];    
        //-----------------//  
    }        
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            'view'=>array(
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'beforeRender'=>'setSessionParentProduct',
                'modelFilter'=>'all',
            ),   
            'create'=>array(
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'service'=>'create'.$this->modelType,
                'createModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'service'=>'update'.$this->modelType,
                'loadModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ),
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
                'service'=>'delete'.$this->modelType,
            ),     
            $this->optionFormGetAction=>array(
                'class'=>'common.widgets.schildform.actions.ChildFormGetAction',
                'stateVariable'=>$this->stateVariable,
                'formKeyStateVariable'=>$this->productStateVariable,
                'formModel'=>$this->formOptionType,
                'mandatoryGetParam'=>'type'
            ),      
            $this->optionFormDelAction=>array(
                'class'=>'common.widgets.schildform.actions.ChildFormDelAction',
                'stateVariable'=>$this->stateVariable,
                'beforeDelete'=>'beforeDeleteAttributes',
            ),            
        ));
    }  
    /**
     * @override
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add($this->defaultScope,Helper::htmlIndexFilter($this->defaultScope, false));
        return $filters->toArray();
    }    
    /**
     * Return the data provider based on scope and searchModel
     * @override
     * @see ShopParentController
     * @return CDbCriteria
     */
    public function getDataProvider($scope,$searchModel=null)
    {
        if ($this->hasParentProduct()){
            
            if (!$this->module->serviceManager->checkObjectAccess(user()->getId(),$this->getParentProduct())){
                logWarning('Unauthrozied access', $this->getParentProduct()->getAttributes());
                throwError403(Sii::t('sii','Unauthorized Access'));
            }
            
            $type = $this->modelType;
            $type::model()->resetScope();
            $finder = $type::model()->{$scope}($this->getParentProduct()->id);
            logTrace($type.'->'.$scope.'('.$this->getParentProduct()->id.')',$finder->getDbCriteria());
            return new CActiveDataProvider($finder, array(
                          'criteria'=>array(
                                'order'=>$this->sortAttribute.' DESC'),
                          'pagination'=>array('pageSize'=>Config::getSystemSetting('record_per_page')),
                          'sort'=>false,
                     ));
        }
        else
            throwError404(Sii::t('sii','Product not found'));
        
    }        
    
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($this->getParentProduct()->id,'update',$id);
            $form->loadLocaleAttributes($form->getAttributeExclusion());
            //need to explicitly load account_id as it is in attribute exclusion
            //purpose is to have page menu "update" icon set to 'active'
            $form->account_id = $form->product->account_id;
            $form->options = $form->getChildForms();
            SActiveSession::load($this->stateVariable, $form, 'options');
        }
        else {
            //limit check on pre-create scenario
            $form = new $this->formType($this->getParentProduct()->id,'precreate');
            if (!$form->validate(array('id','product_id'))) {//specify id field as not to trigger other mandatory fields validation rules
                user()->setFlash($this->modelType,array('message'=>Helper::htmlErrors($form->getErrors()),
                    'type'=>'notice',
                    'title'=>Sii::t('sii','Product Attribute Creation'))); 
                $this->redirect(array('index'));
                Yii::app()->end();
            }
            //proceed form creation - create scenario
            $form->setScenario('create');//there is a scenario "create" validation rule
        }
        return $form;        
    }
    
    public function setFormAttributes($form,$json=false)
    {
        $form->assignLocaleAttributes($_POST[$this->formType],$json);
        //logTraceDump(__METHOD__.' '.$this->formType.' assignLocaleAttributes'.($json?' with json':''),$form->attributes);
        if (isset($_POST[$this->formOptionType])){
            SActiveSession::clear($this->stateVariable);//we will use newly submitted $_POST[$this->formOptionType] as new base
            foreach ($_POST[$this->formOptionType] as $data) {//scan through all options
                //[1]reinstantiate child form
                $childForm = $form->instantiateChildForm($data,'attr_id');
                //[2]setup new session options based on newly submitted $_POST[$this->formChildType]
                SActiveSession::add($this->stateVariable, $childForm);
            }//end for loop
            $form->options = SActiveSession::load($this->stateVariable);
        }
        else {
            $form->options = array();            
        }
        //logTrace(__METHOD__.' $form->getErrors()',$form->getErrors());
        return $form;
    }
    
    public function setModelAttributes($form)
    {
        //[1]copy form attributes to model attributes
        $form->modelInstance->attributes = $form->getModelAttributes();
        //[2]copy form options attributes to model options attributes
        $form->modelInstance->options = $form->getChildModels();
        return $form;
    }  
    
    public function beforeDeleteAttributes($key)
    {
        $model = ProductAttributeOption::model()->findByPk($key);
        if ($model!=null && $model->hasAssociations()){
            return array('status'=>'failure','key'=>$key,'message'=>$this->getFlashAsString('error',Sii::t('sii','Option has inventories and cannot be deleted.'),Sii::t('sii','Option Delete Error')));
        }
        else {
            return array('status'=>'success');
        }
    }
    /*
     * get attribute form and data
     */
    public function actionAttrFormGet($col,$val,$pid)
    {
        if(isset($pid) && isset($col) && isset($val)) {
            header('Content-type: application/json');
            echo $this->_getAttrFormJsonData($pid, $col, $val);
            Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }  
        
    private function _loadProductModel($id)
    {
        $model=Product::model()->findByPk($id);
        if($model===null)
            throwError404(Sii::t('sii','The requested product does not exist.'));
        return $model;
    }        
    /**
     * TODO: used by autocomplete widget
     * @return type
     */
    protected function getAllowedAttrNamesAsArray()
    {
        $product = $this->getParentProduct();
        $list = new CList();
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('obj_type'=>$product->tableName()));
        foreach (Attribute::model()->mine()->findAll($criteria) as $attr){
            if (!ProductAttribute::model()->exists('product_id='.$product->id.' and name=\''.$attr->name.'\''))
                $list->add($attr->name);
        }
        logTrace(__METHOD__,$list->toArray());
        return $list->toArray();
    }
    /**
     * TODO: used by autocomplete widget
     * @return type
     */
    protected function getAllowedAttrCodesAsArray()
    {
        $product = $this->getParentProduct();
        $list = new CList();
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('obj_type'=>$product->tableName()));
        foreach (Attribute::model()->mine()->findAll($criteria) as $attr){
            if (!ProductAttribute::model()->exists('product_id='.$product->id.' and code=\''.$attr->code.'\''))
                $list->add($attr->code);
        }
        logTrace(__METHOD__,$list->toArray());
        return $list->toArray();
    }

    private function _getAttrFormJsonData($pid,$col,$val)
    {
        $product = $this->_loadProductModel($pid);
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('obj_type'=>$product->tableName()));
        $criteria->addColumnCondition(array($col=>$val));
        logTrace('$criteria',$criteria);
        $attr = Attribute::model()->mine()->find($criteria);
        if ($attr===null){
            return CJSON::encode(array('status'=>'failure','message'=>Sii::t('sii','Attribute {field} not found',array('{field}'=>$col))));
        }
        else{
            $model = new $this->modelType;
            $model->product_id = $product->id;
            $model->attributes = $attr->getAttributes(array('name','code','type'));
            SActiveSession::clear($this->stateVariable);
            foreach ($attr->options as $option) {
                logTrace('$option',$option->getAttributes());
                $modelOption = new ProductAttributeOption();
                $modelOption->id = $option->id + time();
                $modelOption->attributes = $option->getAttributes(array('name','code'));
                SActiveSession::add($this->stateVariable, $modelOption);
            }
            return CJSON::encode(array(
                    'status'=>'success',
                    'form'=>$this->renderPartial('_form',array('model'=>$model),true),
                    'name'=>$model->name,
                    'code'=>$model->code,
                    'allowed'=>array('name'=>$this->getAllowedAttrNamesAsArray($product->id),
                                   'code'=>$this->getAllowedAttrCodesAsArray($product->id)),
            ));
        }
    }

    protected function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Attribute Options
        $sections->add(array('id'=>'options','name'=>Sii::t('sii','Attribute Options'),'heading'=>true,'top'=>true,
                             'viewFile'=>'_options','viewData'=>array('dataProvider'=>$model->searchOptions())));
        return $sections->toArray();
    }      
  
    protected function loadChildFormWidget($selector)
    {
        $type = ProductAttribute::TYPE_SELECT;
        $runScript = <<<EOJS
$(document).ready(function(){
    $('#$selector').change(function() {
          clearerror();
          if ($('#$selector').val()==$type)
              $('.add-button').show();
          else {
              clearerror();
              $('.add-button').hide();
              removeallattroptions();
          }
    });                
    $('.add-button').click(function(){
        if ($('#$selector').length > 0){
            clearerror();
            getattroption($('#$selector').val());
        }
    });
});
EOJS;
        $this->widget('common.widgets.schildform.SChildForm', array(
                    'stateVariable' => $this->stateVariable,
                    'deleteControl' => 'all',
                    'headerData'=>array(
                        ProductAttributeOption::model()->getAttributeLabel('name'),
                        ProductAttributeOption::model()->getAttributeLabel('code'),
                        ProductAttributeOption::model()->getAttributeLabel('surcharge'),
                    ),
                    'runScript' => $runScript,
                ));
    }
}