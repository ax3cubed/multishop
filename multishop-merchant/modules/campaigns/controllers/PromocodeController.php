<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('campaigns.controllers.CampaignBaseController');
/**
 * Description of PromocodeController
 *
 * @author kwlok
 */
class PromocodeController extends CampaignBaseController 
{
    protected $formType = 'CampaignPromocodeForm';   
        
    public function init()
    {
        parent::init();
        $this->hasImage = false;
        $this->pageTitleAttribute = 'code';
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = Sii::t('sii','Campaigns');        
        $this->showBreadcrumbsController = true;
        $this->breadcrumbsControllerName = CampaignPromocode::model()->displayName(Helper::PLURAL);        
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'CampaignPromocode';
        $this->route = 'campaigns/promocode/index';
        $this->viewName = Sii::t('sii','Promocode Campaigns');
        $this->sortAttribute = 'update_time';
        //-----------------
        // SPageIndex Configuration
        //-----------------
        $this->searchMap['campaign'] = 'code';//override mapping
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'CampaignPromocodeFilterForm';
        $this->filterFormHomeUrl = url('campaigns/promocode');
        //-----------------//
    }       
    
}
