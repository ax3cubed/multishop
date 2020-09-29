<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.spagefilter.models.SPageFilterForm');
/**
 * Description of CampaignGenericFilterForm
 * 
 * @author kwlok
 */
class CampaignGenericFilterForm extends CampaignFilterForm 
{
    public $type = 'CampaignSale';//use this as dummy but will search into all campaign types   
    /**
     * Initializes this model.
     */
    public function init()
    {
        parent::init();
        //override setting
        $this->config['campaign']['htmlOptions']['placeholder'] = Sii::t('sii','Enter any campaign name or promocode');
    }      
    
    public function getOfferTypes()
    {
        $sale = new CampaignSale();
        $promocode = new CampaignPromocode();
        $bga = new CampaignBga();
        return array_merge($sale->getOfferTypes(),$promocode->getOfferTypes(),$bga->getOfferTypes());
    }
    
}
