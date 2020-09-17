<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopParentController');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ShopParentController 
{
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->loadSessionParentShop();
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = Sii::t('sii','Campaigns');        
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'CampaignSale';//dummy model type 
        $this->viewName = Sii::t('sii','Campaigns Management');
        $this->route = 'campaigns/management/index';
        $this->enableViewOptions = false;
        $this->enableSearch = true;
        $this->customWidgetView = true;
        $this->includePageFilter = true;
        $this->searchMap = [
            'campaign' => 'name',
            'date' => 'create_time',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
            'status' => 'status',
            'offer_type' => 'offer_type',
        ];         
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'CampaignGenericFilterForm';
        $this->filterFormHomeUrl = url('campaigns/management');
        //-----------------//
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
        $filters->add('bga',Helper::htmlIndexFilter(Sii::t('sii','BGA Campaigns'), false));
        $filters->add('sale',Helper::htmlIndexFilter(Sii::t('sii','Sale Campaigns'), false));
        $filters->add('promocode',Helper::htmlIndexFilter(Sii::t('sii','Promocode Campaigns'), false));
        return $filters->toArray();
    }  
    /**
     * OVERRIDE METHOD
     * since we set $customWidgetView to true in init()
     * @see SPageIndexController
     * @return string view file name
     */
    public function getWidgetView($view,$scope=null,$searchModel=null)
    {
        return $this->widget($this->getModule()->getClass('listview'), array(
                    'id'=> 'all',
                    'dataProvider'=> $this->getAllCampaigns($searchModel),
                    'template'=>'{summary}{items}{pager}',
                    'itemView'=>'_campaign',
                    'htmlOptions'=>array('class'=>'campaign list-view'),
                ),true);
    }      
    /**
     * Get all campaigns of different variants
     * @return \CArrayDataProvider
     */
    public function getAllCampaigns($searchModel=null)
    {
        $campaigns = new CMap();
        //set finder
        if ($this->hasParentShop()){
            $saleFinder = CampaignSale::model()->mine()->locateShop($this->getParentShop()->id);
            $promocodeFinder = CampaignPromocode::model()->mine()->locateShop($this->getParentShop()->id);
            $bgaFinder = CampaignBga::model()->mine()->locateShop($this->getParentShop()->id);
        }
        else {
            $saleFinder = CampaignSale::model()->mine();
            $promocodeFinder = CampaignPromocode::model()->mine();
            $bgaFinder = CampaignBga::model()->mine();
        }
        //merge search model search criteria
        if ($searchModel!=null){
            $saleFinder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());
            $promocodeFinder->getDbCriteria()->mergeWith($this->getSearchCriteria($searchModel,true));//re-prepare search criteria for promocode model
            $bgaFinder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());
        }

        //search sales
        foreach ($saleFinder->findAll() as $campaign) {
            $campaigns->add($campaign->update_time,$campaign);//use update_time as key for sorting
        }
        //search promocode
        foreach ($promocodeFinder->findAll() as $campaign) {
            $campaigns->add($campaign->update_time,$campaign);//use update_time as key for sorting
        }
        //search bgas
        foreach ($bgaFinder->findAll() as $campaign) {
            $campaigns->add($campaign->update_time,$campaign);
        }
        return new CArrayDataProvider(Helper::sortArrayByKey($campaigns->toArray(),'DESC'),array(
                                          'pagination'=>array('pageSize'=>Config::getSystemSetting('record_per_page')),
                                          'sort'=>false,
                                     ));
    }      
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @param type $model
     * @param type $promocode customized parameter to differentiate promocode
     * @return CDbCriteria
     */
    public function getSearchCriteria($model,$promocode=false)
    {
        $type =  $this->modelType;
        
        $criteria = new CDbCriteria;
        if ($promocode)
            $criteria->compare('code',$model->name,true);//name (an attribute in proxy model CampaignSale) is mapped to code
        else
            $criteria = QueryHelper::parseLocaleNameSearch($criteria, 'name', $model->name);
        
        $criteria->compare('offer_type',$model->offer_type);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        $criteria = QueryHelper::prepareDateCriteria($criteria, 'start_date', $model->start_date);
        $criteria = QueryHelper::prepareDateCriteria($criteria, 'end_date', $model->end_date);
        if ($model->status==CampaignFilterForm::STATUS_EXPIRED)
            $criteria->mergeWith($type::model()->wentExpired()->getDbCriteria());
        else
            $criteria->compare('status', QueryHelper::parseStatusSearch($model->status,CampaignFilterForm::STATUS_FLAG));

        logTrace(__METHOD__.' criteria',$criteria);
        
        return $criteria;
    }     
}
