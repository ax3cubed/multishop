<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopParentController');
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
/**
 * Description of ProductBaseController
 *
 * @author kwlok
 */
class ProductBaseController extends ShopParentController 
{
    use ImageControllerTrait;
    
    protected $stateVariable = 'undefined';      
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        // check if module requisites exists
        $missingModules = $this->getModule()->findMissingModules();
        if ($missingModules->getCount()>0)
            user()->setFlash($this->getId(),[
                'message'=>Helper::htmlList($missingModules),
                'type'=>'notice',
                'title'=>Sii::t('sii','Missing Module'),
            ]);  
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->loadSessionParentShop();
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
    
    public function setSessionParentShop($model)
    {
        $this->setSessionShop($model->shop);
    }

    public function setSessionParentProduct($model)
    {
        $this->setSessionProduct($model->product);
    }    
}
