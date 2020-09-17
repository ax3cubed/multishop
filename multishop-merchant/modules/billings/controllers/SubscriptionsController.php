<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SubscriptionsController
 *
 * @author kwlok
 */
class SubscriptionsController extends SPageIndexController  
{
    use BillingControllerTrait;
    
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Subscriptions');
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Subscription';
        $this->defaultScope = 'history';
        $this->viewName = Sii::t('sii','Subscription History');
        $this->route = 'billings/subscriptions/index';
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
                'pageTitleAttribute'=>'subscription_no',
            ),                    
        ));
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
