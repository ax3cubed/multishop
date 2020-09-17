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
class ManagementController extends ShopParentController 
{     
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
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = Question::model()->displayName(Helper::PLURAL);        
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Question';
        $this->route = 'questions/management/index';
        $this->viewName = Sii::t('sii','Questions Management');
        $this->enableViewOptions = false;
        $this->enableSearch = false;
        $this->sortAttribute = 'question_time';       
        //$this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->modelFilter = 'merchant';        
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->serviceNotAvailableAction,
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
                'modelFilter'=>'merchant',
                'accountAttribute'=>'answer_by',
                'serviceInvokeParam'=>'answer_by',
                'pageTitleAttribute'=>'question',
            ), 
            'update'=>array(
                'class'=>'common.components.actions.UpdateAction',
                'service'=>'updateAnswer',
                'serviceInvokeParam'=>'answer_by',
                'serviceOwnerAttribute'=>'answer_by',
                'loadModelMethod'=>'prepareModel',
                'model'=>$this->modelType,
                'viewUrl'=>'merchantViewUrl',
            ), 
            'activate'=>array(
                'class'=>'TransitionAction',
                'modelFilter'=>'merchant',
                'nameAttribute'=>'question',
                'modelType'=>$this->modelType,
                'validate'=>array('scenario'=>'activate','field'=>'type'),
                'flashTitle'=>Sii::t('sii','{object} Activation',array('{object}'=>Question::model()->displayName())),
                'flashMessage'=>Sii::t('sii','Question is activated successfully.'),
            ),
            'deactivate'=>array(
                'class'=>'TransitionAction',
                'modelFilter'=>'merchant',
                'nameAttribute'=>'question',
                'modelType'=>$this->modelType,
                'flashTitle'=>Sii::t('sii','{object} Deactivation',array('{object}'=>Question::model()->displayName())),
                'flashMessage'=>Sii::t('sii','Question is deactivated successfully.'),
            ),        
        ));
    }  
    /**
     * Prepare model for update update
     * @param type $id
     * @return \modelType
     */
    public function prepareModel($id=null)
    {
        if (isset($id)){//update action
            $model = $this->loadModel($id);
            $model->answer = $model->htmlbr2nl($model->answer);
        }
        return $model;
    }
    /**
     * Answer a question
     */
    public function actionAnswer() 
    {
        if (Yii::app()->request->getIsPostRequest()){
            $model = $this->loadModel($_POST['id']);
            if ($this->module->getServiceManager('account_id')->checkObjectAccess(user()->getId(),$model->getReference())){
                $form = new AnswerForm($model->id);
                $form->answerUrl = $this->getModule()->taskAnswerUrl;
                $form->formView = '_form';
                header('Content-type: application/json');
                echo CJSON::encode($this->renderPartial($this->getAction()->getId(),array('model'=>$form),true));
                Yii::app()->end();
            }
            logWarning('Unauthorized attempt answer question '.$model->id, $model->getAttributes());
        }
        else {
            $model = $this->loadModel($_GET['id']);
            if ($model->hasAnswer()){
                user()->setFlash(get_class($model),array(
                        'message'=>Sii::t('sii','You had previously answered this question.'),
                        'type'=>'notice',
                        'title'=>Sii::t('sii','Answer Question')));
                $this->redirect($model->merchantViewUrl);
                Yii::app()->end();
            }
            if ($this->module->getServiceManager('account_id')->checkObjectAccess(user()->getId(),$model->getReference())){
                $form = new AnswerForm($model->id);
                $form->answerUrl = $this->getModule()->taskAnswerUrl;
                $form->formView = '_form';
                logTrace('form attributes', $form->getAttributes());
                $this->render('answer',array('model'=>$form));
                Yii::app()->end();
            }
            logWarning('Unauthorized attempt answer question '.$model->id, $model->getAttributes());
         }
         throwError403(Sii::t('sii','Unauthorized Access'));       
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
        $filters->add('asked',Helper::htmlIndexFilter('Asked', false));
        $filters->add('answered',Helper::htmlIndexFilter('Answered', false));
        return $filters->toArray();
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
        if ($this->hasParentShop()){
            $finder = $type::model()->{$this->modelFilter}($this->getParentShop()->id)->{$scope}();
            logTrace($type.'->'.$this->modelFilter.'('.$this->getParentShop()->id.')->'.$scope.'()',$finder->getDbCriteria());
        }
        else {
            $finder = $type::model()->{$this->modelFilter}()->{$scope}();
            logTrace($type.'->'.$this->modelFilter.'()->'.$scope.'()',$finder->getDbCriteria());
        }
        if ($searchModel!=null)
            $finder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());
        return new CActiveDataProvider($finder, array(
                  'criteria'=>array(
                        'order'=>$this->sortAttribute.' DESC'),
                  'pagination'=>array('pageSize'=>Config::getSystemSetting('record_per_page')),
                  'sort'=>false,
                ));
    } 
    
    public function getQuestionUrl($model)
    {
        if ($model->answer===null)
            return $model->answerUrl;
        else
            return $model->merchantViewUrl;        
    }
}