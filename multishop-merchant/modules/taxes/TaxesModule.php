<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of TaxesModule
 *
 * @author kwlok
 */
class TaxesModule extends SModule 
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
                'name'=>'taxes',
                'pathAlias'=>'taxes.assets',
        ]];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'taxes.models.*',
            'taxes.components.*',
            'taxes.behaviors.*',
            'common.modules.taxes.models.Tax',
            'common.modules.shops.controllers.ShopParentController',
            'common.widgets.SButtonColumn',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'tasks'=>[
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.models.*',
                ],
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
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
        return Sii::t('sii','Tax|Taxes',array($mode));
    }    
    /**
    * @return ServiceManager
    */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.TaxManager',
                'model'=>['Tax'],
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}