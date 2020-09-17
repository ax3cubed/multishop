<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ShippingsModule
 *
 * @author kwlok
 */
class ShippingsModule extends SModule 
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
                'name'=>'shippings',
                'pathAlias'=>'shippings.assets',
        ]];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'shippings.models.*',
            'shippings.components.*',
            'shippings.actions.*',
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.widgets.SButtonColumn',
        ]);
        // import module dependencies classes
        $this->setDependencies(array(
            'modules'=>array(
                'tasks'=>array(
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.models.*',
                ),
            ),
            'classes'=>array(
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ),
            'images'=>array(
                'search'=>array('common.assets.images'=>'search.png'),                              
            ),                        
        ));             

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
        return Sii::t('sii','Shipping|Shippings',[$mode]);
    }    
    /**
    * @return ServiceManager
    */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.ShippingManager',
                'model'=>['Shipping','Zone'],
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}