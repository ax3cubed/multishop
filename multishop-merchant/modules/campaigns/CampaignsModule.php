<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of CampaignsModule
 *
 * @author kwlok
 */
class CampaignsModule extends SModule 
{
    /**
     * parentShopModelClass (model classname) means that this module needs to be attached to shop module 
     * as all products objects creation/update is assuming having shop_id in session 
     * 
     * parentShopModelClass (null or false) means that products module needs to define which shop products objects 
     * belongs to during creation/update 
     * 
     * @see SActiveSession::SHOP_ACTIVE
     * @property boolean whether parentShopModelClass is required.
     */
    public $parentShopModelClass = 'Shop';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'campaigns',
                'pathAlias'=>'campaigns.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'campaigns.models.*',
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.widgets.SButtonColumn',
            'common.widgets.simagemanager.SImageManager',
            'common.widgets.simagemanager.models.SingleImageForm',
        ]);

        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'tasks'=>[
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.models.*',
                ],                            
                'products'=>[
                    'application.modules.products.actions.ShippingFormGetAction',
                ],                            
            ],
            'classes'=>[
                'gridview'=>'common.widgets.SGridView',
                'listview'=>'common.widgets.SListView',     
                'itemcolumn'=>'common.widgets.SItemColumn',                            
            ],
            'images'=>[
                'search'=>['common.assets.images'=>'search.png'],
                'datepicker'=>['common.assets.images'=>'datepicker.gif'],
            ],
        ]);        
        
        $this->defaultController = 'management';

        $this->registerScripts();

    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.CampaignManager',
                'model'=>['CampaignBga','CampaignSale','CampaignPromocode'],
            ],
        ]);        
        return $this->getComponent('servicemanager');
    }

}