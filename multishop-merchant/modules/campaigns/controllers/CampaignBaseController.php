<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopParentController');
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
/**
 * Description of CampaignBaseController
 *
 * @author kwlok
 */
class CampaignBaseController extends ShopParentController 
{
    use ImageControllerTrait, TransitionControllerActionTrait;
    protected $stateVariable;      
    protected $hasImage = true;
    protected $pageTitleAttribute = 'name';
    protected $ckeditorImageUploadAction = 'ckeditorimageupload';
    protected $formType ='undefined';
    
    public function init()
    {
        parent::init();
        // check if module requisites exists
        $missingModules = $this->getModule()->findMissingModules();
        if ($missingModules->getCount()>0)
            user()->setFlash($this->getId(),array('message'=>Helper::htmlList($missingModules),
                                            'type'=>'notice',
                                            'title'=>Sii::t('sii','Missing Module')));  
        $this->pageTitle = Sii::t('sii','Campaign Management');  
        //-----------------
        // @see ImageControllerTrait
        $this->setSessionActionsExclude();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->loadSessionParentShop();
        $this->includePageFilter = true;
        //-----------------
        // SPageIndex Configuration
        //-----------------
        $this->searchMap = [
            'campaign' => 'name',
            'date' => 'create_time',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
            'status' => 'status',
            'offer_type' => 'offer_type',
        ];   
    }
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @see ImageControllerTrait::runBeforeAction()
     */
    protected function beforeAction($action)
    {
        return $this->runBeforeAction($action,$this->stateVariable);
    } 
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        $actions = array_merge(parent::actions(),$this->transitionActions(),[
            'view'=>array(
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>$this->pageTitleAttribute,
            ),                     
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
                'service'=>'delete'.$this->modelType,
            ),
            $this->ckeditorImageUploadAction=>array(
                'class'=>'common.components.actions.CkeditorImageUploadAction',
            ),
        ]);
        
        if ($this->hasImage){
            $actions = array_merge($actions,$this->imageActions(),[
                'create'=>array(
                    'class'=>'common.components.actions.LanguageCreateAction',
                    'form'=>$this->formType,
                    'service'=>'create'.$this->modelType,
                    'createModelMethod'=>'prepareForm',
                    'setAttributesMethod'=>'setFormAttributes',
                ),
                'update'=>array(
                    'class'=>'common.components.actions.LanguageUpdateAction',
                    'form'=>$this->formType,
                    'service'=>'update'.$this->modelType,
                    'loadModelMethod'=>'prepareForm',
                    'setAttributesMethod'=>'setFormAttributes',                
                ),                
            ]);
        }
        else {
            $actions = array_merge($actions,[
                'create'=>array(
                    'class'=>'common.components.actions.LanguageCreateAction',
                    'form'=>$this->formType,
                    'service'=>'create'.$this->modelType,
                    'createModelMethod'=>'prepareForm',
                    'setAttributesMethod'=>'setFormAttributes',
                ),
                'update'=>array(
                    'class'=>'common.components.actions.LanguageUpdateAction',
                    'form'=>$this->formType,
                    'service'=>'update'.$this->modelType,
                    'loadModelMethod'=>'prepareForm',
                    'setAttributesMethod'=>'setFormAttributes',                
                ),                
            ]);
            
        }
        return $actions; 
    }        
    /**
     * @see CampaignBaseController::actions() definition
     * @param type $id
     * @return \modelType
     */
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes();
        }
        else {
            $form = new $this->formType;
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
        }
        return $form;
    }    
    /**
     * @see CampaignBaseController::actions() definition
     * @param type $model
     * @return type
     */
    public function setFormAttributes($form,$json=false)
    {
        $form->assignLocaleAttributes($_POST[$this->formType],$json);
        return $form;
    }      
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return CDbCriteria
     */
    public function getSearchCriteria($model)
    {
        $type =  $this->modelType;
        
        $criteria = new CDbCriteria;
        //search into model name
        $criteria = QueryHelper::parseLocaleNameSearch($criteria, $this->searchMap['campaign'], $model->{$this->searchMap['campaign']});
        $criteria->compare('offer_type',$model->offer_type);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        $criteria = QueryHelper::prepareDateCriteria($criteria, 'start_date', $model->start_date);
        $criteria = QueryHelper::prepareDateCriteria($criteria, 'end_date', $model->end_date);
        if ($model->status==CampaignFilterForm::STATUS_EXPIRED)
            $criteria->mergeWith($type::model()->wentExpired()->getDbCriteria());
        else
            $criteria->compare('status', QueryHelper::parseStatusSearch($model->status,CampaignFilterForm::STATUS_FLAG));
        return $criteria;
    }        
}

