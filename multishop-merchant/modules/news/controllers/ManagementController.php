<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
/**
 * Description of ManagementController for News
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController
{
    use TransitionControllerActionTrait, ImageControllerTrait;

    protected $formType = 'NewsForm';    
    
    public function init()
    {
        parent::init();
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::NEWS_IMAGE; 
        $this->setSessionActionsExclude();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = News::model()->displayName(Helper::PLURAL);        
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'News';
        $this->viewName = Sii::t('sii','News Blog');
        $this->route = 'news/management/index';
        $this->sortAttribute = 'update_time';
        //-----------------//
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude();
        //-----------------//
        
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
        return array_merge(parent::actions(),$this->imageActions(),$this->transitionActions(false,false,'headline'),array(
            'view'=>array(
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'headline',
            ),                    
            'create'=>array(
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
                'nameAttribute'=>'headline',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'nameAttribute'=>'headline',
            ), 
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
                'nameAttribute'=>'headline',
            ),
        ));
    }  
    /**
     * Prepare model for update
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
        
}
