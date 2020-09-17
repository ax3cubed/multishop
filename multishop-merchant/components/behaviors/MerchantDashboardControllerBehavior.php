<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.analytics.components.DashboardControllerBehavior');
Yii::import('common.modules.analytics.components.ChartFactory');
Yii::import('common.modules.analytics.charts.ShopVisitsContainerChart');
/**
 * Description of MerchantDashboardControllerBehavior
 *
 * @author kwlok
 */
class MerchantDashboardControllerBehavior extends DashboardControllerBehavior
{
    /**
     * @see DashboardControllerBehavior::initBehavior()
     */
    public function initBehavior() 
    {
        if (user()->isGuest)
            throwError403(Sii::t('sii','Unauthorized Access'));
    }
    /**
     * @see DashboardControllerBehavior::loadScopeFilters()
     * @return array
     */
    public function loadScopeFilters()
    {
        $filters = new CMap();
        if (user()->isAuthorized){
            foreach (ChartFactory::getMerchantCharts() as $chart) {
                $filters->add($chart['id'],Helper::htmlIndexFilter(ChartFactory::getChartName($chart['id']), false));
            }
        }
        return $filters->toArray();
    }    
    /**
     * @see DashboardControllerBehavior::loadWidgetView()
     * @return array
     */
    public function loadWidgetView($view,$scope,$searchModel=null)
    {
        if ($this->getOwner()->hasParentShop()){
            $view = '';
            foreach (ChartFactory::getMerchantCharts($this->getOwner()->getParentShop()->id,$this->getOwner()->getParentShop()->currency,$scope) as $chart)
                $view .= $this->renderChartWidget($chart);
            return CHtml::tag('div',array('class'=>'shop-dashboard'),$view);
        }
        else {//below is mainly called from WelcomeController
            $sections = new CList();
            foreach (Shop::model()->mine()->approved()->findAll() as $key => $model){
                $view = '';
                foreach (ChartFactory::getMerchantQuickCharts($model->id,$model->currency) as $chart)
                    $view .= $this->renderChartWidget($chart);
                $sections->add(array(
                    'id'=>$model->id,
                    'name'=>CHtml::link($model->displayLanguageValue('name',user()->getLocale()),$model->viewUrl),
                    'heading'=>true,'top'=>$key==0?true:false,
                    'html'=>$view,
                    'htmlOptions'=>array('class'=>'shop-dashboard')
                ));
            }
            return $this->getOwner()->widget('SPageSection', array('sections'=>$sections->toArray()),true);        
        }
    }

    public function loadCharts() 
    {
        if ($this->getOwner()->hasParentShop())
            return ChartFactory::getMerchantCharts($this->getOwner()->getParentShop()->id,$this->getOwner()->getParentShop()->currency,$this->getOwner()->getScope());
        else {
            logError(__METHOD__.' no parent shop',[],false);
            return [];
        }
    }

    public function loadDefaultScope() 
    {
        return ShopVisitsContainerChart::ID;
    }

    public function renderPageIndex($content) 
    {
        $this->getOwner()->getPageIndex($content);
    }
    
}
