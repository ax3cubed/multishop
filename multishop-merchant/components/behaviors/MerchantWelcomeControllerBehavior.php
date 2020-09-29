<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of MerchantWelcomeControllerBehavior
 *
 * @author kwlok
 */
class MerchantWelcomeControllerBehavior extends WelcomeControllerBehavior
{
    /**
     * @see WelcomeControllerBehavior::initBehavior()
     */
    public function initBehavior() 
    {
        if (user()->isGuest)
            throwError403(Sii::t('sii','Unauthorized Access'));
        
        //-----------------
        // SPageIndex Configuration - cont'd
        // @see SPageIndexController
        if (!user()->isActivated && user()->account->pendingSignup()){//user is registered only - normally come from social network
            $this->getOwner()->customWidgetView = true;
            $this->getOwner()->pageControl = null;//not set
            $this->getOwner()->loadPreSignupMessages();
        }            
        else if ((user()->isActivated && !user()->isAuthorized) || !user()->hasShops) {
            user()->setFlash($this->getOwner()->id,[
                'message'=>Sii::t('sii','Congratulations! You are one step away from starting your business.'),
                'type'=>'success',
                'title'=>Sii::t('sii','Welcome to {app} family.',['{app}'=>param('SITE_NAME')]),
            ]);
            $this->getOwner()->defaultScope = 'welcome';//to display default scope page at welcome page
            $this->getOwner()->customWidgetView = true;
            $this->getOwner()->pageControl = null;//not set
        }
        elseif (user()->isAuthorizedActivated) {
            $this->getOwner()->defaultScope = 'dashboard';//to display default scope page at welcome page
        }
        $this->getOwner()->controlCallback = $this->getControlCallback();
        $this->getOwner()->modelType = $this->getOwner()->module->welcomeModel;//to display recent orders at welcome page
    }
    /**
     * @see WelcomeControllerBehavior::loadScopeFilters()
     * @return array
     */
    public function loadScopeFilters()
    {
        $filters = new CMap();
        if (user()->isAuthorized){
            $filters->add('dashboard',Helper::htmlIndexFilter(Sii::t('sii','Dashboard'), false));
            $filters->add('orders',Helper::htmlIndexFilter(Sii::t('sii','Shipping Orders'), false));
            $filters->add('items',Helper::htmlIndexFilter(Sii::t('sii','Items'), false));
            $filters->add('tasks',Helper::htmlIndexFilter(Sii::t('sii','Tasks'), false));
        }
        else //for account has no merchant role yet
            $filters->add('all',Sii::t('sii','All'));
        return $filters->toArray();
    }    
    /**
     * @see WelcomeControllerBehavior::loadWidgetView()
     * @return array
     */
    public function loadWidgetView($view,$scope,$searchModel=null)
    {
        switch ($scope) {
            case 'dashboard':
                return $this->getOwner()->module->runControllerMethod('analytics/management','getWidgetView');//all the 3 arguments for signature are null and safe to pass in
            case 'orders':
                $this->getOwner()->modelView = $this->getOwner()->module->getView('orders.merchantorderlist');
                return $this->getOwner()->renderPartial('_orders',[],true);
            case 'items':
                $this->getOwner()->modelType = 'Item';
                $this->getOwner()->modelFilter = 'merchant';
                $this->getOwner()->modelView = $this->getOwner()->module->getView('items.merchantitemlist');
                return $this->getOwner()->renderPartial('_items',[],true);
            case 'tasks':
                $this->getOwner()->modelFilter = 'merchant';
                $this->getOwner()->tasksView = 'tasklist';
                return $this->getOwner()->renderPartial('_tasks',array('role'=>Role::MERCHANT),true);
            case 'welcome':
                return $this->getOwner()->renderPartial('welcome',[],true);
            case 'activate':
                return $this->getOwner()->renderPartial('activate',[],true);
            default:
                throwError404(Sii::t('sii','The requested page does not exist'));
        }
    }     
    
    public function getRecentItems()
    {
        return new CActiveDataProvider(Item::model()->{$this->getOwner()->modelFilter}()->all(), [
            'criteria'=> [ 'order'=>'create_time DESC'],
            'pagination'=> ['pageSize'=>Config::getSystemSetting('record_per_page')],
            'sort'=>false,
        ]);
    }    
    
    public function getRecentNews()
    {
        $finder = News::model()->mine()->recently();
        return new CActiveDataProvider($finder, array(
                                            'criteria'=>array(),
                                            'pagination'=>array('pageSize'=>Config::getSystemSetting('news_per_page')),
                                        ));        
    }    
    
    public function getRecentOrders()
    {
        $type = $this->getOwner()->modelType;
        return new CActiveDataProvider($type::model()->mine()->all(), [
              'criteria'=>['order'=>'create_time DESC'],
              'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
              'sort'=>false,
         ]);
    }    
    
    public function getOrderExtendedSumary()
    {
        $output = '<span class="extendedSummary"> | '.CHtml::link(Sii::t('sii','Show All POs'), url('purchase-orders')).'</span>';
        $output .= '<span class="extendedSummary"> | '.CHtml::link(Sii::t('sii','Show All SOs'), url('shipping-orders')).'</span>';
        return $output;
    }
    /**
     * OVERRIDDEN
     * @see WelcomeControllerBehavior::showRecentMessages()
     */
    public function showSidebar()
    {
        return user()->isAuthorized;
    }   
    /**
     * OVERRIDDEN
     * @see WelcomeControllerBehavior::showRecentActivities()
     */
    public function showRecentActivities()
    {
        return user()->isAuthorized;
    }       
    /**
     * OVERRIDDEN
     * @see WelcomeControllerBehavior::showRecentMessages()
     */
    public function showRecentMessages()
    {
        return user()->isAuthorized;
    }      
    /**
     * OVERRIDDEN
     * @see WelcomeControllerBehavior::showRecentNews()
     */
    public function showRecentNews()
    {
        return false;
    }       
    /**
     * A callback when view page control is changed
     * @return type
     */
    public function getControlCallback()
    {
        return [
            'dashboard'=> CHtml::encode('function(){refreshdashboard(\'/account/welcome/dashboard\');}'),
        ];
    }
    /**
     * Below action is called when js:quickdashboard() is invoked - when user is switching 'Arrow Tabs' at index page
     */
    public function actionDashboard()
    {
        header('Content-type: application/json');
        $widget = new CMap();
        foreach (Shop::model()->mine()->approved()->findAll() as $model){
            foreach (ChartFactory::getMerchantQuickCharts($model->id,$model->currency) as $data) {
                $widget->add($data['id'].$model->id,$this->getChartWidgetData($data,$model->id,$model->currency));
            }
        }
        echo CJSON::encode($widget->toArray());
        Yii::app()->end();
    }
    /**
     * For action Dashboard use
     * @return type
     */
    public function getChartWidgetData() 
    {
        $widget = new CMap();
        foreach (Shop::model()->mine()->approved()->findAll() as $model){
            foreach (ChartFactory::getMerchantQuickCharts($model->id,$model->currency) as $data) {
                $widget->add($data['id'].$model->id,ChartFactory::getChartWidgetInitData([
                    'id'=>$data['id'],
                    'type'=>$data['type'],
                    'filter'=>$data['filter'],
                    'shop'=>$model->id,
                    'currency'=>$model->currency,
                    'selection'=>null,
                ]));
            }
        }
        return $widget->toArray();
    }    
    /**
     * OVERRIDDEN
     * @return boolean Default to false
     */
    public function renderAdvices()
    {
        return $this->getOwner()->merchantWizard->renderAdvices();
    }         
}
