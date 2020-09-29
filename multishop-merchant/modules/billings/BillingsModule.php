<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.extensions.braintree.BraintreeApi');
Yii::import('common.modules.payments.plugins.braintreeCreditCard.components.BraintreeApiTrait');
/**
 * Description of BillingsModule
 *
 * @author kwlok
 */
class BillingsModule extends SModule 
{
    use BraintreeApiTrait;
    
    public $paymentGateway;    
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'billings',
                'pathAlias'=>'billings.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'billings.webhooks.*',
            'billings.models.*',
            'billings.controllers.*',
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.widgets.spagelayout.SPageLayout',
        ]);

        // import module dependencies classes
        $this->setDependencies([
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',                
            ],
            'sii'=>[
                //must follow this format [app-alias.module-name]
                'common.plans',
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
                'class'=>'common.services.BillingManager',
                'model'=>['Billing'],
                'runMode'=>$this->serviceMode,
                'paymentGateway'=>$this->paymentGateway,
            ],
        ]);
        return $this->getComponent('servicemanager');
    }

}
