<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ProductAttributeForm
 *
 * @author kwlok
 */
class ProductAttributeForm extends LanguageParentForm 
{
    private $_p;//product model instance
    /*
     * Inherited attributes
     */
    public $model = 'ProductAttribute';
    /**
     * Controlled attributes
     * @see LanguageParentForm
     */
    protected $parentKeyAttribute = 'product_id';
    protected $exclusionAttributes = array('account_id','shop_id');//ProductAttribute family models do not have these inherited attributes
    protected $persistentAttributes = array('options');
    protected $childFormKeyAttribute = 'product_id';
    protected $childFormClass = 'ProductAttributeOptionForm';
    protected $childFormAttribute = 'options';
    protected $childFormModelAttributes = array('attr_id','name','code','surcharge');
    /*
     * Local attributes
     */
    public $product_id;
    public $code;
    public $name;
    public $type;
    public $share;
    public $create_time;       
    public $options=array();     
    /**
     * Customzied Constructor.
     * Make product_id is a mandatory argument
     * 
     * @see LanguageForm::__construct()
     */
    public function __construct($product_id,$scenario='',$id=null)
    {
        parent::__construct($id,$scenario);
        $this->id = $id;
        $this->product_id = $product_id;
    }     
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
            array('product_id, code, name, type, share', 'required'),
            array('product_id, type', 'numerical', 'integerOnly'=>true),
            array('share', 'length', 'max'=>1),
            array('code', 'length', 'max'=>2),
            array('name', 'length', 'max'=>50),
            //validate type 
            array('type', 'ruleType'),
            //validate options 
            array('options', 'ruleOptions'),
            //on "create" scenario, id field here as dummy
            array('code', 'ruleCode','on'=>'create'),
            //on "precreate" scenario, id field here as dummy
            array('id', 'ruleAttributeLimit','params'=>array(),'on'=>'precreate'),
            array('product_id', 'ruleInventoryExists','params'=>array(),'on'=>'precreate'),
        );
    } 
    /**
     * Validate type to make sure options is populated
     */
    public function ruleType($attribute,$params)
    {
        if ($this->type==ProductAttribute::TYPE_SELECT){
            if (count($this->options)<=0)
                $this->addError('type',Sii::t('sii','Select Field has no options'));
        }
    }    
    /**
     * Validate code and its uniqueness
     */
    public function ruleCode($attribute,$params)
    {
        $this->modelInstance->product_id = $this->product_id;//product_id is assigned at contstructor
        $this->modelInstance->code = $this->code;
        $this->modelInstance->setScenario('create');
        $this->modelInstance->validate(array('code'));
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }    
    /**
     * @see ProductAttribute::ruleAttributeLimit()
     */
    public function ruleAttributeLimit($attribute,$params)
    {
        $this->modelInstance->product_id = $this->product_id;//product_id is assigned at contstructor
        $this->modelInstance->setScenario($this->getScenario());
        $this->modelInstance->ruleAttributeLimit($attribute,$params);
        if ($this->modelInstance->hasErrors($attribute))
            $this->addErrors($this->modelInstance->getErrors($attribute));
    }    
    /**
     * @see ProductAttribute::ruleInventoryExists()
     */
    public function ruleInventoryExists($attribute,$params)
    {
        $this->modelInstance->product_id = $this->product_id;//product_id is assigned at contstructor 
        $this->modelInstance->setScenario($this->getScenario());
        $this->modelInstance->ruleInventoryExists($attribute,$params);
        if ($this->modelInstance->hasErrors($attribute))
            $this->addErrors($this->modelInstance->getErrors($attribute));
    } 
    /**
     * Validate rules of options
     */
    public function ruleOptions($attribute,$params)
    {
        foreach ($this->options as $option) {
            //logTrace(__METHOD__.' '. get_class($option).' attribute',$option->attributes);
            $option->validateLocaleAttributes();
            if ($option->hasErrors()){
                logTraceDump(__METHOD__.' option errors',$option->getErrors());
                $this->addErrors($option->getErrors());
            }
        }//end for loop
    }           
    /**
     * Validate options
     */
    public function validateChildForm() 
    {
        $this->ruleOptions('options',array());
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
