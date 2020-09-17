<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of InventoriesModule
 *
 * @author kwlok
 */
class InventoriesModule extends SModule 
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
     * parentProductModelClass (model classname) means that products module needs to be attached to product module 
     * as all products objects creation/update is assuming having product_id in session 
     * 
     * parentProductModelClass (null or false) means that products module needs to define which product products objects 
     * belongs to during creation/update 
     * 
     * @see SActiveSession::PRODUCT_ACTIVE
     * @property boolean whether parentProductModelClass is required.
     */
    public $parentProductModelClass = 'Product';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'inventories',
                'pathAlias'=>'inventories.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'inventories.models.*',
            'inventories.behaviors.*',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'shops'=>[
                    'common.modules.shops.models.*',
                ],                                                     
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
                'itemcolumn'=>'common.widgets.SItemColumn',
            ],
        ]);  

        $this->defaultController = 'management';

        $this->registerScripts();

    }
    /**
     * Module display name
     * @param $mode singular or plural, if the language supports, e.g. english
     * @return string the model display name
     */
    public function displayName($mode=Helper::SINGULAR)
    {
        return Sii::t('sii','Inventory|Inventories',[$mode]);
    }     
    /**
    * @return ServiceManager
    */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.InventoryManager',
                'model'=>['Inventory'],
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}