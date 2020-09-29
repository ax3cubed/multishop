<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shippings.controllers.ShippingBaseController');
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ShippingBaseController
{
    use TransitionControllerActionTrait;
    
    public    $tierFormGetAction = 'tierformget';
    public    $tierFormDelAction = 'tierformdel';
    protected $formType = 'ShippingForm'; 
    protected $formTierType = 'ShippingTierForm';
    protected $stateVariable = SActiveSession::SHIPPING_TIER;      
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Shipping';
        $this->route = 'shippings/management/index';
        $this->viewName = Sii::t('sii','Shippings Management');
        $this->sortAttribute = 'update_time';
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->tierFormGetAction,
            $this->tierFormDelAction,        
            'products',//for section pagination
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
        if ($referer!=$url){
            if ($action->getId()==$this->tierFormGetAction || $action->getId()==$this->tierFormDelAction){
                logTrace($action->getId().' excluded from clearing SActiveSession');
            }
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
        return array_merge(parent::actions(),$this->transitionActions(false,true),array(
            'products'=>array(
                'class'=>'common.widgets.spagesection.actions.SectionPaginationAction',
                'model'=>$this->modelType,
                'viewFile'=>'_product',
            ), 
            'view'=>array(
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'name',
            ),                    
            'create'=>array(
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'setAttributesMethod'=>'setFormAttributes',
                'setModelAttributesMethod'=>'setModelAttributes',
            ),
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
            ),                    
            $this->tierFormGetAction=>array(
                'class'=>'TierFormGetAction',
                'stateVariable'=>$this->stateVariable,
                'mandatoryGetParam'=>'base',
                'formKeyStateVariable'=>'sid',
                'formModel'=>$this->formTierType,
                'useLanguageForm'=>false,
            ),               
            $this->tierFormDelAction=>array(
                'class'=>'common.widgets.schildform.actions.ChildFormDelAction',
                'stateVariable'=>$this->stateVariable,
            ),
        ));
    }    
    /**
     * Prepare form model
     * @param type $id
     * @return \formType
     */
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes($form->getAttributeExclusion());
            $form->tiers = $form->getChildForms();
            $form->setTierBase($form->getTierBase());
            if (!$form->hasTierBase())
                $form->createTierBase();
            SActiveSession::load($this->stateVariable, $form, 'tiers');            
        }
        else {//create action
            $form = new $this->formType();
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
            else {
                if (isset($_GET['sid'])){
                    $shop = $this->loadModel($_GET['sid'], 'Shop');
                    $this->setSessionShop($shop);
                    $form->shop_id = $shop->id;
                }
            }
            $form->createTierBase();
        }
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
        if ($this->hasParentShop())
            $form->shop_id = $this->getParentShop()->id;
        
        if (isset($_POST[$this->formTierType]['base'])){
            $form->setTierBase($_POST[$this->formTierType]['base']);//assign back tierBase selection
            unset($_POST[$this->formTierType]['base']);//remove this 'base' key as not required by below processing
        }
                    
        if ($form->type==Shipping::TYPE_TIERS && isset($_POST[$this->formTierType])){
            SActiveSession::clear($this->stateVariable);;//we will use newly submitted $_POST[$this->formTierType] as new base
            foreach ($_POST[$this->formTierType] as $data) {
                //[1]reinstantiate child form
                $childForm = $form->instantiateChildForm($data,'shipping_id');
                //[2]setup new session options based on newly submitted $_POST[$this->formChildType]
                SActiveSession::add($this->stateVariable, $childForm);
            }         
            $form->tiers = SActiveSession::load($this->stateVariable);
        }
        //logTrace(__METHOD__.' $form->getErrors()',$form->getErrors());
        
        return $form;
    }      
    public function setModelAttributes($form)
    {
        //[1]copy form attributes to model attributes
        $form->modelInstance->attributes = $form->getModelAttributes();
        //[2]copy form tiers attributes to model options attributes
        $form->modelInstance->tiers = $form->getChildModels();
        return $form;
    }      
    protected function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Shipping Method
        $sections->add(array('id'=>'method','name'=> Sii::t('sii','Shipping Method'),'heading'=>true,'top'=>true,
                             'viewFile'=>'_method','viewData'=>array('dataProvider'=>$model->searchMethod())));
        if ($model->type==Shipping::TYPE_TIERS){
            //section 2: Shipping Tiers
            $sections->add(array('id'=>'tiers','name'=> Sii::t('sii','Shipping Tiers'),'heading'=>true,
                                 'viewFile'=>'_tier','viewData'=>array('dataProvider'=>$model->searchTiers())));
        }
        //section 3: Products
        $sections->add(array('id'=>'products','name'=> Sii::t('sii','Associated Products'),'heading'=>true,
                             'viewFile'=>'_product','viewData'=>array('model'=>$model)));

        return $sections->toArray();
    }  

    protected function loadChildFormWidget($model)
    {
        $selector = get_class($model).'_type';
        $baseSelector = $this->formTierType.'_base';
        $typeFlat = Shipping::TYPE_FLAT;
        $typeTiers = Shipping::TYPE_TIERS;
        $runScript = '$(document).ready(function(){';
        $runScript .= <<<EOJS
$('#$selector').change(function() {
    resetshippingtier();
    if ($('#$selector').val()==$typeFlat){showrate();}
    else if ($('#$selector').val()==$typeTiers){showbase()}
    else {hiderate();}
});
$('#$baseSelector').change(function() {resetshippingtier();});
$('.add-button').click(function(){if ($('#$baseSelector').val().length > 0){clearerror();getshippingtier($('#$baseSelector').val());}});
EOJS;
        
        if ($model->type==Shipping::TYPE_TIERS){
            $runScript .= 'showselectbase();';
        }
        $runScript .= '});';
    
        $this->widget('common.widgets.schildform.SChildForm', array(
                    'stateVariable' => $this->stateVariable,
                    'htmlOptions' => array(
                        'id'=>'row_tiers',
                        'style'=>'margin-top:60px;clear:both;'.($model->type==Shipping::TYPE_TIERS?'':'display:none'),
                    ),
                    'headerData'=>array(
                        $model->tierBase->getAttributeLabel('floor'),
                        $model->tierBase->getAttributeLabel('ceiling'),
                        $model->tierBase->getAttributeLabel('rate'),
                    ),
                    'runScript' => $runScript,
                ));
    }    
}
