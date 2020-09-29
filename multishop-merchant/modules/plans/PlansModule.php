<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of PlansModule
 *
 * @author kwlok
 */
class PlansModule extends SModule 
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
                'name'=>'plans',
                'pathAlias'=>'plans.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'billings.models.Billing',
            'common.widgets.SButtonColumn',
            'common.widgets.spageindex.controllers.SPageIndexController',
        ]);

        // import module dependencies classes
        $this->setDependencies([
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ],
            'views'=>[
                'subscriptions'=>'common.modules.plans.views.subscription._package_listview',
                'planitemview'=>'common.modules.plans.views.management._planitem_gridview',
                'planworkflowview'=>'common.modules.plans.views.package._plan_gridview_workflow',
                'plangridview'=>'common.modules.plans.views.package._plan_gridview',
                'history'=>'tasks.processhistory',
                'profilesidebar'=>'accounts.profilesidebar',
            ],
            'sii'=>[
                //must follow this format [app-alias.module-name]
                'common.billings',
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
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.PlanManager',
                'model'=>[
                    'Plan',
                    'Package',
                    'Subscription',
                ],
                'runMode'=>$this->serviceMode,
            ],
        ]);
        return $this->getComponent('servicemanager');
    }

}
