<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.shippings.behaviors.ShippingBehavior");
/**
 * Description of ShippingForm
 *
 * @author kwlok
 */
class ShippingForm extends LanguageParentForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'Shipping';
    /**
     * Controlled attributes
     * @see LanguageParentForm
     */
    protected $persistentAttributes = array('type','tiers');
    protected $exclusionAttributes = array('tierBase');//Exclude attributes not belong to underlying model
    protected $childFormClass = 'ShippingTierForm';
    protected $childFormAttribute = 'tiers';
    protected $childFormKeyAttribute = 'shop_id';
    protected $childFormModelAttributes = array('shipping_id','base','floor','ceiling','rate');
    /*
     * Local attributes
     */
    public $zone_id;
    public $name;
    public $method;
    public $type;
    public $rate;
    public $speed;
    public $status;
    public $tiers=array();    
    public $tierBase;//used for validation when type=SHIPPING_TIER is selected, internall use a dummy ShippingTier model
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'shippingbehavior' => array(
                'class'=>'ShippingBehavior',
            ),
        ));
    }   
    /**
     * @return array Definitions of form attributes that requires multi languages
     */       
    public function localeAttributes()
    {
        return array(
            'name'=>array(
                'required'=>true,
                'inputType'=>'textField',
                'inputHtmlOptions'=>array('size'=>60,'maxlength'=>50),
            ),
        );
    }    
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
            array('account_id, shop_id, zone_id, name, method, type', 'required'),
            array('account_id, shop_id, zone_id, method, type, speed', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>50),
            array('status', 'length', 'max'=>10),                    
            //validate type 
            array('type', 'ruleType'),
            // validate 'rate' field to make sure correct rate is entered
            array('rate', 'type', 'type'=>'float','allowEmpty'=>true),
            array('rate', 'default', 'setOnEmpty'=>true, 'value' => null),
            array('rate', 'length', 'max'=>10),
            array('rate', 'ruleRate'),
            //validate tiers 
            array('tiers', 'ruleTiers'),
        ));
    } 
    /**
     * Validate type to make sure options is populated
     */
    public function ruleType($attribute,$params)
    {
        if ($this->type==Shipping::TYPE_TIERS){
            if (count($this->tiers)<=0){
                $this->addError('type',Sii::t('sii','Tiered Fee has no tier definition'));
            }
        }
    }        
    /**
     * @see Shipping::ruleRate()
     */
    public function ruleRate($attribute,$params)
    {
        if ($this->type!=null){
            $this->modelInstance->type = $this->type;//these two fields are required for validation
            $this->modelInstance->rate = $this->rate;
            $this->modelInstance->validate(array('rate'));
            if ($this->modelInstance->hasErrors($attribute)){
                $this->addErrors($this->modelInstance->getErrors());
            }
        }
    }    
    /**
     * Validation rules of tiers
     */
    public function ruleTiers($attribute,$params)
    {
        $ruleCount = 0; $prevCeiling = 0;
        foreach ($this->tiers as $tier) {
            //logTrace(__METHOD__.' '. get_class($tier).' attribute',$tier->attributes);
            $tier->validateLocaleAttributes();
            //check against previous tier
            if ($ruleCount>0){
                if ($tier->floor <= $prevCeiling){
                    logTrace(__METHOD__.' current tier floor '.$tier->floor.' <= previous tier ceiling '.$prevCeiling);
                    $tier->addError('floor',Sii::t('sii','Floor cannot be equal or smaller than lower-tier Ceiling'));          
                }
            }
            $ruleCount++;
            $prevCeiling = $tier->ceiling;            
            
            if ($tier->hasErrors()){
                logTraceDump(__METHOD__.' tier errors',$tier->getErrors());
                $this->addErrors($tier->getErrors());
            }
            
        }//end for loop
    }     
    /**
     * Validate tiers
     */
    public function validateChildForm() 
    {
        $this->ruleTiers('tiers',array());
    }
    /**
     * @return model attributes to be copied
     */
    public function getModelAttributes()
    {
        $attributes = $this->getAttributes();
        unset($attributes['model']);
        unset($attributes['tiers']);
        unset($attributes['tierBase']);
        return $attributes;
    }    
    /**
     * Check has a tier base
     */
    public function hasTierBase()
    {
        return $this->tierBase != null;
    }   
    /**
     * Set a tier base
     */
    public function setTierBase($base)
    {
        if ($base instanceof ShippingTierForm)
            $this->tierBase = $base;
        else {
            $this->createTierBase();
            $this->tierBase->base = $base;
        }
    }
    /**
     * Create a tier base (using a dummy ShippingTier to house base)
     */
    public function createTierBase()
    {
        $this->tierBase = new ShippingTierForm($this->{$this->childFormKeyAttribute});
    }
    /**
     * Return status text
     * @param type $color
     * @return type
     */
    public function getStatusText($color=true)
    {
        return $this->modelInstance->getStatusText($color);
    }

}
