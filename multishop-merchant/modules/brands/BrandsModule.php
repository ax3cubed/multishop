<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of BrandModule
 *
 * @author kwlok
 */
class BrandsModule extends SModule 
{
    /**
     * parentShopModelClass (model classname) means that products module needs to be attached to shop module 
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
              'name'=>'brands',
              'pathAlias'=>'brands.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'brands.models.*',
            'common.modules.shops.controllers.ShopParentController',
            'common.widgets.simagemanager.SImageManager',
            'common.widgets.simagemanager.models.SingleImageForm',
        ]);        
        // import module dependencies classes
        $this->setDependencies(array(
            'modules'=>[
                'shops'=>[
                    'application.modules.shops.models.*',
                ],
            ],
            'classes'=>[
                'gridview'=>'common.widgets.SGridView',
                'listview'=>'common.widgets.SListView',
                'itemcolumn'=>'common.widgets.SItemColumn',
            ],
            'views'=>[
                'productthumbnail'=>'application.modules.brands.views.management._product',
            ],        
            'images'=>[
                'search'=>array('common.assets.images'=>'search.png'),
            ],
        ));  
        
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
                'class'=>'common.services.BrandManager',
                'model'=>'Brand',
                'htmlError'=>true,
            ],
        ]);
        return $this->getComponent('servicemanager');
    }

}
