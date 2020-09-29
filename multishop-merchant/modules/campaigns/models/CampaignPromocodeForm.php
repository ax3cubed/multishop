<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of CampaignPromocodeForm
 *
 * @author kwlok
 */
class CampaignPromocodeForm extends CampaignBaseForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'CampaignPromocode';    
    /*
     * Local attributes
     */
    public $code;
    public $offer_type;
    public $offer_value;
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
        return array_merge(parent::rules(),array(
            array('code, offer_value, offer_type', 'required'),
            array('code', 'length', 'max'=>12),
            array('code', 'match', 'pattern'=>'/^[a-zA-Z0-9]+$/', 'message'=>Sii::t('sii','Promocode accepts only letters or digits.')),
            array('offer_value', 'length', 'max'=>8),
            array('offer_value', 'ruleOfferAmount','min'=>1,'max'=>100),
        ));
    }     
    /**
     * @see CampaignPromocode::ruleOfferAmount()
     */
    public function ruleOfferAmount($attribute,$params)
    {
        $this->modelInstance->offer_value = $this->offer_value;
        $this->modelInstance->offer_type = $this->offer_type;
        $this->modelInstance->ruleOfferAmount($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors ($this->modelInstance->getErrors());
    }     
}
