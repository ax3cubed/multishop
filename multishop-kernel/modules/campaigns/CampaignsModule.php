<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CampaignsModule
 * Module placeholder for components, models, and behaviors classes commonly shared by other multishop-apps
 * Note: No controllers and views 
 * 
 * @author kwlok
 */
class CampaignsModule extends SModule 
{

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'campaigns.models.*',
            'campaigns.components.*',
        ]);
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