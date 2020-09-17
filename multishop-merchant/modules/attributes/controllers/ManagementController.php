<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends SPageIndexController 
{
    protected $modelOptionType = 'AttributeOption';
    protected $sessionActionsExclude = array();
    protected $stateVariable = SActiveSession::ATTRIBUTE;
    public    $optionFormGetAction = 'optionformget';
    public    $optionFormDelAction = 'optionformdel';
    
    public function init()
    {
        parent::init();
        $this->sessionActionsExclude = array($this->optionFormGetAction,$this->optionFormDelAction);
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Attribute';
        $this->viewName = Attribute::model()->displayName(Helper::PLURAL);
        $this->route = 'attributes/management/index';
        $this->sortAttribute = 'update_time';
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
            if (in_array($action->getId(), $this->sessionActionsExclude))
               logTrace($action->getId().' excludes clearing '.$this->stateVariable);
            else {
                logTrace('clear '.$this->stateVariable);
                SActiveSession::clear($this->stateVariable);
            }
        }
        return true;
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
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            'view'=>array(
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
            ),   
            'create'=>array(
                'class'=>'common.components.actions.CreateAction',
                'model'=>$this->modelType,
                'createModelMethod'=>'prepareModel',
                'setAttributesMethod'=>'setModelAttributes',
            ),
            'update'=>array(
                'class'=>'common.components.actions.UpdateAction',
                'model'=>$this->modelType,
                'loadModelMethod'=>'prepareModel',
                'setAttributesMethod'=>'setModelAttributes',
            ),
            'delete'=>array(
                'class'=>'common.components.actions.DeleteAction',
                'model'=>$this->modelType,
            ),                    
            $this->optionFormGetAction=>array(
                 'class'=>'OptionFormGetAction',
                 'stateVariable'=>$this->stateVariable,
                 'optionModel'=>$this->modelOptionType,
            ),
            $this->optionFormDelAction=>array(
                 'class'=>'OptionFormDelAction',
                 'stateVariable'=>$this->stateVariable,
            ),
        ));
    }        
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights - '.$this->optionFormGetAction.', '.$this->optionFormDelAction, 
        );
    }  
    /**
     * Return the data provider based on scope and searchModel
     * 
     * @see SPageIndex
     * @return CDbCriteria
     */
    public function getDataProvider($scope,$searchModel=null)
    {
        $type = $this->modelType;
        $type::model()->resetScope();
        $finder = $type::model()->{$this->modelFilter}()->{$scope}();
        if ($searchModel!=null)
            $finder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());
        logTrace($type.'->'.$this->modelFilter.'()->'.$scope.'()',$finder->getDbCriteria());
        return new CActiveDataProvider($finder, array(
                                                  'criteria'=>array(
                                                        'order'=>$this->sortAttribute.' DESC'),
                                                  'pagination'=>array('pageSize'=>Config::getSystemSetting('record_per_page')),
                                                  'sort'=>false,
                                             ));
    }  
    public function prepareModel($id=null)
    {
        if (isset($id)){//update action
            $model = $this->loadModel($id);
            SActiveSession::load($this->stateVariable, $model, 'options');
        }
        else {//create action
            $model = new $this->modelType('create');
        }
        return $model;
    }
    public function setModelAttributes($model)
    {
        $model->attributes = $_POST[$this->modelType];

        if (isset($_POST[$this->modelType]['Option'])){
           logTrace(__METHOD__.' $_POST['.$this->modelType.'][Option]',$_POST[$this->modelType]['Option']);
           SActiveSession::clear($this->stateVariable);//we will use newly submitted $_POST[$this->_modelType]['Option'] as new base
           foreach ($_POST[$this->modelType]['Option'] as $option) {
                $_option = new $this->modelOptionType;
                $_option->attributes=$option;
                if ($_option->attr_id==null)
                    $_option->attr_id = 0;//temp assigned id for validation use only
                SActiveSession::add($this->stateVariable, $_option);
            }//end for loop
            $model->options = SActiveSession::load($this->stateVariable);
           
        }
        return $model;        
    }
        
    protected function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Attribute Options
        $sections->add(array('id'=>'options','name'=>Sii::t('sii','Attribute Options'),'heading'=>true,'top'=>true,
                             'viewFile'=>'_options','viewData'=>array('dataProvider'=>$model->searchOptions())));
        //section 2: Attribute Usage
        $sections->add(array('id'=>'usage','name'=>Sii::t('sii','Attribute Usage'),'heading'=>true,
                             'viewFile'=>'_usage','viewData'=>array('dataProvider'=>$model->searchAttributeUsage())));
        return $sections->toArray();
    }      
}