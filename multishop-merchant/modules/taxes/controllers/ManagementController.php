<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController 
{
    use TransitionControllerActionTrait;
    
    protected $formType = 'TaxForm';    
    /**
    * Initializes the controller.
    */
    public function init()
    {
        parent::init();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Tax';
        $this->route = 'taxes/management/index';
        $this->viewName = Sii::t('sii','Taxes Management');
        $this->sortAttribute = 'update_time';
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),$this->transitionActions(false,true),array(
            'view'=>array(
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'name',
            ),              
            'create'=>array(
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
            ),
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
            ),                    
        ));
    }  
    /**
     * Prepare model data before create/update
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

}
