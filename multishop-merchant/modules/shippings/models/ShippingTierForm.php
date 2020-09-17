<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ShippingTierForm
 *
 * @author kwlok
 */
class ShippingTierForm extends LanguageChildForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'ShippingTier';
    /**
     * Controlled attributes
     * @see LanguageChildForm
     */
    public    $keyAttribute = 'shop_id';    
    protected $exclusionAttributes = array('account_id');//shop_id is included
    protected $persistentAttributes = array('floor');
    /*
     * Local attributes
     */
    public $shipping_id;
    public $base;
    public $floor;
    public $ceiling;
    public $rate;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(             
            'schildformbehavior' => array(
                'class'=>'common.widgets.schildform.behaviors.SChildFormBehavior',
                'deleteScriptName'=>'removeshippingtier',
                'hiddenAttributes'=>array('id','shipping_id','base'),
                'nonLocaleAttributes'=>array(
                    'floor'=>array(
                        'textPrefixCallback'=>'getBaseUnit',
                        'size'=>10,
                        'maxlength'=>11,
                    ),
                    'ceiling'=>array(
                        'textPrefixCallback'=>'getBaseUnit',
                        'size'=>10,
                        'maxlength'=>11,
                    ),
                    'rate'=>array(
                        'textPrefixCallback'=>'getCurrencyUnit',
                        'size'=>8,
                        'maxlength'=>11,
                    ),
                ),
            ),            
        ));
    }     
    /**
     * locale attributes
     */
    public function localeAttributes() 
    {
        return array();//this form has no locale attributes
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('shipping_id, base, floor, rate', 'required'),
            array('id, shipping_id, base', 'numerical', 'integerOnly'=>true),
            array('floor, ceiling, rate', 'length', 'max'=>10),
            array('floor, rate', 'type','type'=>'float', 'allowEmpty'=>false),
            array('ceiling', 'default', 'setOnEmpty'=>true, 'value' => null),
            array('ceiling', 'ruleCeiling'),
        );
    }    
    /**
     * Validate rate ceiling
     */
    public function ruleCeiling($attribute,$params)
    {
        if ($this->ceiling!=null)
            if ($this->ceiling<=$this->floor)
                $this->addError('ceiling',Sii::t('sii','Ceiling must be larger than Floor'));      
    }
    
    public function getBaseUnit()
    {
        return $this->base==ShippingTier::BASE_SUBTOTAL?$this->shop->getCurrency():$this->shop->getWeightUnit();
    }
    
    public function getCurrencyUnit()
    {
        return $this->shop->getCurrency();
    } 
     
}
