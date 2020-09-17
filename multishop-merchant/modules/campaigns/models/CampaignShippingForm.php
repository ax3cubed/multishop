<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('merchant.modules.products.models.ProductShippingForm');
/**
 * Description of CampaignShippingForm
 *
 * @author kwlok
 */
class CampaignShippingForm extends ProductShippingForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'CampaignShipping';    
    /**
     * Controlled attributes
     * @see LanguageChildForm
     */
    public $keyAttribute = 'campaign_id';    
    protected $persistentAttributes = [];    
    /*
     * Local attributes
     */    
    public $campaign_id;
    /**
     * Validation rules for locale attributes
     * 
     * Note: that all different locale values of one attributes are to be stored in db table column
     * Hence, model attribute (table column) wil have separate validation rules following underlying table definition
     * 
     * @return array validation rules for locale attributes.
     */
    public function rules()
    {
        return array(
            array('campaign_id, shipping_id', 'required'),
            array('id, campaign_id, shipping_id', 'numerical', 'integerOnly'=>true),
            array('surcharge', 'length', 'max'=>10),
            array('surcharge', 'type', 'type'=>'float'),
            array('surcharge', 'default', 'setOnEmpty'=>true, 'value' => null),
            array('surcharge', 'safe'),
        );
    }    
}
