<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopParentController');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController 
{
    protected $stateVariable = SActiveSession::INVENTORY;      
    protected $sessionActionsExclude = array();
    public $skuFormGetAction = 'skuformget';
    public $skuPartialFormGetAction = 'skupartialformget';
    public $productVariable = 'product';//call from product controller view/update inventory shortcut

    public function init()
    {
        parent::init();
        $this->sessionActionsExclude = array($this->skuFormGetAction,$this->skuPartialFormGetAction);
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Inventory';
        $this->route = 'inventories/management/index';
        $this->viewName = Sii::t('sii','Inventory Management');
        $this->sortAttribute = 'update_time';
        $this->enableSearch = false;
        //-----------------//
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->skuFormGetAction,
            $this->skuPartialFormGetAction,
            $this->serviceNotAvailableAction,            
            'history',//for pagination use
        ];
        //-----------------//   
    }
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param CAction $action the action to be executed.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action)
    {
        $referer = request()->getUrlReferrer();
        $url = request()->getHostInfo().request()->getUrl();
        //logTrace(__METHOD__." referer=$referer url=$url");
        //below support the session product loading for correct action
        //the page are refered from product view 
        if (strpos($referer, 'product')!=false){
            $this->loadSessionParentProduct();
        }
        else {
            $this->unloadSessionParentProduct();//always unload session product 
        }
        
        if ($referer!=$url){
            if (in_array($action->getId(), $this->sessionActionsExclude))
               logTrace(__METHOD__.' '.$action->getId().' excludes clearing '.$this->stateVariable);
            else {
                logTrace('clear '.$this->stateVariable);
                SActiveSession::clear($this->stateVariable);
            }
        }
        return true;
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
            ), 
            'history'=>array(
                'class'=>'common.widgets.spagesection.actions.SectionPaginationAction',
                'model'=>$this->modelType,
                'viewFile'=>'_history',
            ), 
            'create'=>array(
                'class'=>'common.components.actions.CreateAction',
                'model'=>$this->modelType,
                'service'=>'create'.$this->modelType,
                'createModelMethod'=>'prepareModel',
                'setAttributesMethod'=>'setFormAttributes',
                'nameAttribute'=>'sku',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>'InventoryForm',
                'service'=>'update'.$this->modelType,
                'loadModelMethod'=>'prepareModel',
                'setAttributesMethod'=>'setFormAttributes',
                'setExtraParamsMethod'=>'setServiceExtraParams',
                'nameAttribute'=>'sku',
            ),
            'delete'=>array(
                'class'=>'common.components.actions.DeleteAction',
                'model'=>$this->modelType,
                'service'=>'delete'.$this->modelType,
                'nameAttribute'=>'sku',
            ),                             
        ));
    }  
    /**
     * Return the data provider based on scope and searchModel
     * 
     * @override
     * @see ShopParentController
     * @return CDbCriteria
     */
    public function getDataProvider($scope,$searchModel=null)
    {
        $type = $this->modelType;
        $type::model()->resetScope();
        logTrace(__METHOD__.' scope='.$scope.' and $_GET params',$_GET);
            
        if ($scope=='lowStock' && $this->hasParentShop())
            $finder = $type::model()->{$this->modelFilter}()->{$scope}($this->getParentShop()->getLowInventoryThreshold());
        else
            $finder = $type::model()->{$this->modelFilter}()->{$scope}();

        if ($this->hasParentShop()){
            $finder = $type::model()->{$this->modelFilter}()->locateShop($this->getParentShop()->id);
            $scope = 'locateShop';//for below logging purpose
        }
        if (isset($_GET[$this->productVariable])){
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(array(
                'obj_type'=>Product::model()->tableName(),
                'obj_id'=>$_GET[$this->productVariable]));
            $finder->getDbCriteria()->mergeWith($criteria);
            $scope .= '()->product';//for below logging purpose
            $this->loadSessionParentProduct();//include this will only show current active product inventory
        }
        
        if ($searchModel!=null)
            $finder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());
        
        logTrace(__METHOD__.' '.$type.'->'.$this->modelFilter.'()->'.$scope.'()',$finder->getDbCriteria());
        
        return new CActiveDataProvider($finder, array(
                                                  'criteria'=>array(
                                                        'order'=>$this->sortAttribute.' DESC'),
                                                  'pagination'=>array('pageSize'=>Config::getSystemSetting('record_per_page')),
                                                  'sort'=>false,
                                             ));        
    }   
    
    public function prepareModel($id=null)
    {
        $this->loadSessionParentProduct();//include this will only show current active product inventory
                
        if (isset($id)){//update action
            $model = new InventoryForm($id);
            $model->loadLocaleAttributes(array('adjust'));
        }
        else {//create action
            $model = new $this->modelType('create'); //there is a scenario "create" validation rule
            if ($this->hasParentShop())
                $model->shop_id = $this->getParentShop()->id;
            if ($this->hasParentProduct()){
                $model->obj_type = $this->getParentProduct()->tableName();
                $model->obj_id = $this->getParentProduct()->id;
            }
        }
        return $model;
    } 
    
    public function setFormAttributes($model,$json=false)
    {
        if ($model->isNewRecord){
            $model->attributes = $_POST[$this->modelType];
            $model->available = $model->quantity;
        }
        else {
            $model->attributes = $_POST['InventoryForm'];
            if ($model->validate()){
                $model->quantity += $model->adjust;
                $model->available += $model->adjust;
            }
            else {
                logError(__METHOD__.' inventory form validation error', $model->getErrors(), false);
                throw new CException(Sii::t('sii','Validation Error'));
            }           
            $this->adjustValue = $model->adjust;
        }
        return $model;
    }  
    
    protected $adjustValue;//temp placeholder to store adjusted value
    /**
     * As $movement param pass in service manager
     * @return array
     */
    public function setServiceExtraParams()
    {
        return array($this->adjustValue);
    }     
    /*
     * get sku form and data
     * Note: field "Type" for now is not in use, reserved for future use, e.g. support textfield etc
     */
    public function actionSkuFormGet($pid)
    {
        if(isset($pid)) {
             $model = $this->getInitialSKU( $this->_loadProductModel($pid) );
             header('Content-type: application/json');
             echo CJSON::encode(array('status'=>'success',
                                      'form'=>Yii::app()->controller->renderPartial('_form_sku',array('model'=>$model),true),
                    ));
             Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }    
    /*
     * get sku partial form and data
     */
    public function actionSkuPartialFormGet()
    {
        if(isset($_POST['Inventory'])) {
            SActiveSession::clear($this->stateVariable);//clear first before add latest
            logTrace('$_POST[Inventory]',$_POST['Inventory']);
            $model = new Inventory();
            $model->obj_id = $_POST['Inventory']['obj_id']; 
            $model->obj_type = $_POST['Inventory']['obj_type']; 
            $model->sku =  $_POST['Inventory']['product_code'];//not a phsycial table column 
            $attrSelectedMap = new CMap();
            foreach ($_POST['Inventory']['Attribute'] as $key => $attribute) {
                if ($attribute!=null){
                     $model->sku .= Helper::DASH_SEPARATOR.$attribute;
                     $attrSelectedMap->add($key, $attribute);
                }
            }
            SActiveSession::set($this->stateVariable, $attrSelectedMap);
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'status'=>'success',
                'form'=>Yii::app()->controller->renderPartial('_form_sku_partial',array('model'=>$model),true),
            ));
            Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }    
    private function _loadProductModel($id)
    {
        $model=Product::model()->findByPk($id);
        if($model===null)
            throwError404(Sii::t('sii','The requested page does not exist'));
        return $model;
    }     
    protected function getInitialSKU($product=null)
    {
        if ($this->hasParentProduct()){
            $product = $this->getParentProduct();
        }
        $model = new Inventory();
        $model->obj_type = $product->tableName(); 
        $model->obj_id = $product->id; 
        $model->sku = $product->code;
        return $model;
    }
    protected function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Inventory History
        $sections->add(array('id'=>'history','name'=>Sii::t('sii','Inventory History'),'heading'=>true,
                             'viewFile'=>'_history','viewData'=>array('model'=>$model)));
        return $sections->toArray();
    }  
    
    protected function enableSelectAttributeJs()
    {
        cs()->registerScript('chosen1','$(\'.chzn-select-attr\').chosen();$(\'.chzn-container .chzn-search\').hide();$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
        cs()->registerScript('chosen2','$(\'.chzn-select-attr\').change(function(){getskupartialform();});',CClientScript::POS_END);
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter('All', false));
        $filters->add('lowStock',Helper::htmlIndexFilter('Low Stock', false));
        $filters->add('outOfStock',Helper::htmlIndexFilter('Out of Stock', false));
        return $filters->toArray();
    }  
    
}