<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ItemsModule
 *
 * @author kwlok
 */
class ItemsModule extends SModule 
{
    /**
     * @property string the default controller.
     */
    public $entryController = 'undefined';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'items',
                'pathAlias'=>'items.assets',
            ],
        ];
    }
        
    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'items.models.*',
            'items.controllers.*',
            'common.modules.items.models.*',
            'common.modules.orders.models.ShippingOrder',
            'common.widgets.SButtonColumn',
            'common.widgets.spagelayout.SPageLayout',
            'common.widgets.spageindex.controllers.SPageIndexController',
        ]);        
         // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'payments'=>[
                    'common.modules.payments.models.PaymentMethod',
                ],
                'tasks'=>[
                    'common.modules.tasks.models.*',
                    'common.modules.tasks.actions.CommentAction',
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.components.TaskBaseController',
                ],
                'comments'=>[
                    'common.modules.comments.models.Comment',
                    'common.modules.comments.models.CommentForm',
                ],
            ],
            'classes'=>[
                'gridview'=>'common.widgets.SGridView',
                'itemcolumn'=>'common.widgets.SItemColumn',
                'listview'=>'common.widgets.SListView',
                'groupview'=>'common.widgets.SGroupView',
            ],
            'views'=> [
                'merchantitemlist'=>'application.modules.items.views.merchant._item_listview',
                'merchantitems'=>'application.modules.items.views.merchant._items',
                //orders view
                'ordersummary'=>'orders.customersummary',
                'orderpayment'=>'orders.customerpayment',
                'merchantinventory'=>'orders.merchantinventory',
                //common views
                'merchantaddress'=>'common.modules.orders.views.common._address',
                'merchantshipping'=>'common.modules.orders.views.common._shipping',
                'merchantpayment'=>'common.modules.orders.views.common._payment',
                'merchantattachment'=>'common.modules.orders.views.common._attachments',
                'merchanthistory'=>'common.modules.orders.views.common._history',
                //comments view
                'comment'=>'comments.commentview',
                //cart key value view
                'keyvalue'=>'common.modules.carts.views.base._key_value',
            ],
            'images'=>[
                'search'=> ['common.assets.images'=>'search.png'],                            
                'datepicker'=> ['common.assets.images'=>'datepicker.gif'],
            ],
            'sii'=>[
                'common.orders',
            ],
        ]); 

        $this->defaultController = $this->entryController;

        $this->registerScripts();

    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.ItemManager',
                'model'=>'Item',
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
}
