<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('campaigns.controllers.CampaignBaseController');
/**
 * Description of BGAController
 *
 * @author kwlok
 */
class BgaController extends CampaignBaseController 
{
    public    $productInfoGetAction = 'productinfoget';
    public    $offerInfoGetAction = 'offerinfoget';
    public    $shippingFormGetAction = 'shippingformget';
    protected $formType = 'CampaignBgaForm';   
    protected $formShippingType = 'CampaignShippingForm';    
    
    public function init()
    {
        parent::init();
        $this->stateVariable = SActiveSession::CAMPAIGN_BGA_SHIPPING;           
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::CAMPAIGN_BGA_IMAGE; 
        $this->sessionActionsExclude = array_merge($this->sessionActionsExclude,[
            $this->productInfoGetAction,
            $this->offerInfoGetAction,
            $this->shippingFormGetAction    
        ]);        
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = Sii::t('sii','Campaigns');        
        $this->showBreadcrumbsController = true;
        $this->breadcrumbsControllerName = CampaignBga::model()->displayName(Helper::PLURAL);        
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'CampaignBga';
        $this->route = 'campaigns/bga/index';
        $this->viewName = Sii::t('sii','BGA Campaigns');
        $this->sortAttribute = 'update_time';
        $this->pageViewOption = SPageIndex::VIEW_LIST;
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'CampaignBgaFilterForm';
        $this->filterFormHomeUrl = url('campaigns/bga');
        //-----------------
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            $this->ckeditorImageUploadAction,
            $this->productInfoGetAction,
            $this->offerInfoGetAction,
            $this->shippingFormGetAction,
        ]);
        //-----------------//
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            $this->shippingFormGetAction=>array(
                'class'=>'ShippingFormGetAction',
                'stateVariable'=> $this->stateVariable,
                'formModel'=> $this->formShippingType,
                'mandatoryGetParam'=>'ids',
                'formKeyStateVariable'=>'pid',
                'useLanguageForm'=>false,
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
        ));
    }  
    
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes();
            $form->shippings = $form->getChildForms();
            SActiveSession::load($this->stateVariable, $form, 'shippings');            
        }
        else {
            $form = new $this->formType();
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
        }
        return $form;
    }

    public function setFormAttributes($form,$json=false)
    {
        $form->assignLocaleAttributes($_POST[$this->formType],$json);
        if (isset($_POST[$this->formShippingType])){
            SActiveSession::clear($this->stateVariable);//we will use newly submitted $_POST[$this->formShippingType] as new base
            foreach ($_POST[$this->formShippingType] as $shippingForm) {
                //[1]reinstantiate tier form
                $_shipping = new $this->formShippingType($shippingForm['campaign_id']);
                $_shipping->assignLocaleAttributes($shippingForm,true);//serialize multi-lang attribute values
                if ($_shipping->campaign_id==null)
                    $_shipping->campaign_id = 0;//temp assigned id for validation use only
                //[2]transfer back errors if any from previous activity
                foreach ($form->shippings as $__s)
                    $_shipping->addErrors($__s->getErrors());
                //[3]setup new session tier based on newly submitted $_POST[$this->formShippingType]
                logTrace(__METHOD__.' $_shipping->getAttributes()',$_shipping->getAttributes());
                SActiveSession::add($this->stateVariable, $_shipping);
            }//end for loop
            $form->shippings = SActiveSession::load($this->stateVariable);
        }
        return $form;
    }        
    
    public function setModelAttributes($form)
    {
        //[1]copy form attributes to model attributes
        $form->modelInstance->attributes = $form->getModelAttributes();
        //[2]copy form tiers attributes to model options attributes
        $form->modelInstance->shippings = $form->getChildModels();
        return $form;
    }     
    /**
     * Return the array of grid columns (for SPageIndex::VIEW_GRID use)
     * 
     * @see SPageIndexController
     * @return array
     */
    public function getGridColumns()
    {
        return array();
    }
    protected function getSectionsData($model,$form=false) 
    {
        $sections = new CList();
        if (!$form){
            //section 1: Shippings
            $sections->add(array('id'=>'shipping',
                                 'name'=>Sii::t('sii','Shippings'),
                                 'heading'=>true,'top'=>true,
                                 'viewFile'=>'_shipping','viewData'=>array('dataProvider'=>$model->searchShippings())));
            //section 2: Description
            $sections->add(array('id'=>'description',
                                 'name'=>$model->getAttributeLabel('description'),
                                 'heading'=>true,
                                 'html'=>$model->languageForm->renderForm($this,Helper::READONLY,array('description'),true)));
        } 
        else {
            //section 1: Shippings
            $sections->add(array('id'=>'shipping',
                                 'name'=>Sii::t('sii','Shippings'),
                                 'heading'=>true,'top'=>true,
                                 'viewFile'=>'_form_shippings','viewData'=>array('model'=>$model)));
            //section 2: Description
            $sections->add(array('id'=>'description',
                                 'name'=>$model->getAttributeLabel('description'),
                                 'heading'=>true,
                                 'viewFile'=>'_form_description','viewData'=>array('model'=>$model)));
        }
        
        return $sections->toArray();
    }            
    /**
     * Get product info and image
     * @param integer $p the ID of the product model
     */
    public function actionProductInfoGet($p)
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            $model = Product::model()->findbyPk($p);
            if ($model==null)
                throwError404();

            header('Content-type: application/json');
            echo CJSON::encode($this->renderPartial('_product',array('model'=>$model),true));
            Yii::app()->end();
        }
        else
            throwError400(Sii::t('sii','Bad Request'));
    }      
    /**
     * Get campaign offer info
     * @param integer $p the ID of the offer product
     * @param integer $o offer value
     * @param integer $ot offer type
     * @param integer $qty quantity of offer product
     */
    public function actionOfferInfoGet($p,$o,$ot,$qty)
    {
        if(Yii::app()->request->isAjaxRequest) {
            if (isset($p) && isset($o) && !empty($ot) && isset($qty)){//offer type cannot be empty
                $data = Yii::app()->serviceManager->getCampaignManager()->checkProductPrice($p,$qty,array('type'=>'bga','offer_type'=>$ot,'at_offer'=>$o));
                header('Content-type: application/json');
                echo CJSON::encode($this->renderPartial('_offer',array('data'=>$data),true));
                Yii::app()->end();
            }
        }
        else
            throwError400(Sii::t('sii','Bad Request'));
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
        return $filters->toArray();
    }       
    /**
     * TODO method reference for select with group
     * Not in used
     * @param type $shop
     * @return type
     */
    public function getProductInventoryListData($shop)
    {
        $options = new CList();
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('obj_type'=>Product::model()->tableName()));
        $criteria->addColumnCondition(array('shop_id'=>$shop));
        foreach (Inventory::model()->findAll($criteria) as $inventory) {
            $options->add(array(
                'sku'=>$inventory->sku,
                'displayName'=>Sii::t('sii','{sku} ({available})',array('{sku}'=>$inventory->sku,'{available}'=>$inventory->available)),
                'group'=>$inventory->source->name)
            );
        }
        return CHtml::listData($options->toArray(),'sku','displayName','group');
        
    }    

    protected function getShippingRateData($shipping)
    {
        if ($shipping->type==Shipping::TYPE_TIERS)
            return Helper::htmlList($shipping->getShippingRateText());
        else
            return $shipping->getShippingRateText();
    }          
}