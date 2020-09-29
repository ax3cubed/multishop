<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of AttributesModule
 *
 * @author kwlok
 */
class AttributesModule extends SModule 
{
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return array(
            'assetloader' => array(
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'attributes',
                'pathAlias'=>'attributes.assets',
            ),
        );
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport(array(
            'attributes.models.*',
            'attributes.actions.*',
            'common.widgets.spageindex.controllers.SPageIndexController',            
        ));
        // import module dependencies classes
        $this->setDependencies(array(
            'modules'=>array(),
            'classes'=>array(
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ),
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
        $this->setComponents(array(
            'servicemanager'=>array(
                'class'=>'common.services.AttributeManager',
                'model'=>'Attribute',
            ),
        ));
        return $this->getComponent('servicemanager');
    }
    
}
