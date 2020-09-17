<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ShopAddressForm
 *
 * @author kwlok
 */
class ShopAddressForm extends LanguageChildForm
{
    /*
     * Inherited attributes
     */
    public $model = 'ShopAddress';
    /*
     * The primary key attribute and is mandatory for object construction
     */
    public $keyAttribute = 'shop_id';    
    /**
     * Inherited attributes to be excluded and not applicable
     * @see LanguageForm
     */
    protected $exclusionAttributes = ['account_id'];
    /*
     * Local attributes
     */
    public $address1;
    public $address2;
    public $postcode;
    public $city;
    public $state;
    public $country;
    public $create_time;
    public $update_time;
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('shop_id, address1, postcode, city, country', 'required'),
            array('shop_id, postcode', 'numerical', 'integerOnly'=>true),
            array('postcode', 'length', 'max'=>20),
            array('address1, address2', 'length', 'max'=>100),
            array('city, state, country', 'length', 'max'=>40),
            array('id, shop_id, address1, address2, postcode, city, state, country, create_time, update_time','safe'),
        );
    }    
    /**
     * Array of form attributes that require multi language support
     */    
    public function localeAttributes() 
    {        
        return [];//leave blank as no locale attributes
    }     
    /*
     * overriden method
     */
    public function formatErrorName($locale,$attribute)
    {
        return $attribute.$locale;
    }   
    /**
     * Scan through all fields if there is any data
     * @return boolean
     */
    public function hasData()
    {
        foreach ($this->getAttributes(array('address1','address2','postcode','city','state','country')) as $key => $value) {
            if (!empty($value))
                return true;
        }
        return false;
    } 
    /**
     * Check if form has necessary address fields
     */
    public function hasAddress()
    {
        return ($this->address1!=null && $this->city!=null && $this->postcode!=null && $this->country!=null);
    }
    /**
     * @return model attributes to be copied
     */
    public function getModelAttributes()
    {
        $attributes = $this->getAttributes();
        unset($attributes['model']);
        foreach ($this->getAttributeExclusion() as $exclude) {
            unset($attributes[$exclude]);
        }
        return $attributes;
    }    
}
