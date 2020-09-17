<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ProductAttributeOptionForm
 *
 * @author kwlok
 */
class ProductAttributeOptionForm extends LanguageChildForm 
{
    private $_p;//product model instance
    /*
     * Inherited attributes
     */
    public $model = 'ProductAttributeOption';
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
    public $code;
    public $name;
    public $attr_id;
    public $surcharge;
    public $create_time;   
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(             
            'locale' => array(
                'class'=>'common.components.behaviors.LocaleBehavior',
                'ownerParent'=>'shop',
            ),  
            'schildformbehavior' => array(
                'class'=>'common.widgets.schildform.behaviors.SChildFormBehavior',
                'deleteScriptName'=>'removeattroption',
                'hiddenAttributes'=>array('id','attr_id'),
                'nonLocaleAttributes'=>array(
                    'code'=>array(
                        'size'=>3,
                        'maxlength'=>2,
                    ),
                    'surcharge'=>array(
                        'textPrefixCallback'=>'getCurrencyUnit',
                        'textSuffix'=>Sii::t('sii','per item'),
                        'size'=>5,
                        'maxlength'=>10,
                    ),
                ),
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
                'label'=>false,
                'inputType'=>'textField',
                'inputHtmlOptions'=>array('size'=>80,'maxlength'=>50),
            ),
        );
    }    
    /**
     * Overriden: As ProductAttribute has no shop_id attribute
     * @see LanguageForm:locales()
     */
    public function locales()
    {
        $this->setLocales($this->shop->getLanguages());
        return $this->sortLocales($this->getLocales());
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
        return array(//not merging with parent::rules() as it has no account_id and shop_id
            array('attr_id, code, name', 'required'),
            array('id, attr_id', 'numerical', 'integerOnly'=>true),
            array('code', 'length', 'max'=>2),
            array('name', 'length', 'max'=>50),
            array('surcharge', 'length', 'max'=>10),
            array('surcharge', 'type', 'type'=>'float'),
            array('surcharge', 'default', 'setOnEmpty'=>true, 'value' => null),
            array('surcharge', 'safe'),
        );
    }
    
    public function getCurrencyUnit()
    {
        return $this->shop->getCurrency();
    }        
    /**
     * Return product model
     * If $this->product_id has value, it try load underlying model from db
     * @return CModel
     */
    public function getProduct() 
    {
        if (isset($this->product_id)){
            if (!isset($this->_p))
                $this->_p = Product::model()->findByPk($this->product_id);
            return $this->_p;
        }
        else 
            return null;
    }  
    /**
     * Return shop model
     * @return CModel
     */
    public function getShop() 
    {
        if ($this->getProduct()!=null){
            return $this->getProduct()->shop;
        }
        else 
            return null;
    }  
}
