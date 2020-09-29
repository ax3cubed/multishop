<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.schildform.behaviors.SChildFormBehavior');
/**
 * Description of ProductShippingForm
 *
 * @author kwlok
 */
class ProductShippingForm extends LanguageChildForm 
{
    private $_s;//shipping model instance
    /*
     * Inherited attributes
     */
    public $model = 'ProductShipping';    
    /**
     * Controlled attributes
     * @see LanguageChildForm
     */
    public $keyAttribute = 'product_id';    
    protected $persistentAttributes = array();    
    /*
     * Local attributes
     */    
    public $product_id;
    public $shipping_id;
    public $surcharge;
    public $create_time;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(             
            'schildformbehavior' => array(
                'class'=>'common.widgets.schildform.behaviors.SChildFormBehavior',
                'hiddenAttributes'=>array('id','shipping_id'),
                'nonLocaleAttributes'=>array(
                    'status.dummy'=>array(
                        'htmlField'=>false,
                        'rawValueCallback'=>'getStatusText',
                    ),
                    'name.dummy'=>array(
                        'htmlField'=>false,
                        'rawValueCallback'=>'getShippingName',
                    ),
                    'method.dummy'=>array(
                        'htmlField'=>false,
                        'rawValueCallback'=>'getShippingMethod',
                    ),
                    'shipping_rate.dummy'=>array(
                        'htmlField'=>false,
                        'rawValueCallback'=>'getShippingRateText',
                    ),
                    'surcharge'=>array(
                        'textPrefixCallback'=>'getCurrencyUnit',
                        'size'=>3,
                        'maxlength'=>10,
                    ),
                ),
                'deleteButton'=>false,
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
            array('product_id, shipping_id', 'required'),
            array('id, product_id, shipping_id', 'numerical', 'integerOnly'=>true),
            array('surcharge', 'length', 'max'=>10),
            array('surcharge', 'type', 'type'=>'float'),
            array('surcharge', 'default', 'setOnEmpty'=>true, 'value' => null),
            array('surcharge', 'safe'),
        );
    }    
    /**
     * If $this->shipping_id has value, it try load underlying model from db
     * @return shipping model
     */
    public function getShipping() 
    {
        if (isset($this->shipping_id)){
            if (!isset($this->_s))
                $this->_s = Shipping::model()->findByPk($this->shipping_id);
            return $this->_s;
        }
        else 
            return null;
    }      
    /**
     * Return shipping name
     * @return type
     */
    public function getShippingName()
    {
        return $this->getShipping()->displayLanguageValue('name',user()->getLocale());
    }
    /**
     * Return shipping method
     * @return type
     */
    public function getShippingMethod()
    {
        return $this->getShipping()->getMethodDesc();
    }
    /**
     * Return shipping rate text
     * @return type
     */
    public function getShippingRateText() 
    {
        if ($this->getShipping()->type == Shipping::TYPE_TIERS)
            return Helper::htmlList($this->getShipping()->getShippingRateText());
        else
            return $this->getShipping()->getShippingRateText();
    }    
    /**
     * Return status text
     * @return type
     */
    public function getStatusText()
    {
        return Helper::htmlColorText($this->getShipping()->getStatusText());
    }
    
    public function getCurrencyUnit()
    {
        return $this->getShipping()->shop->getCurrency();
    } 
    
}
