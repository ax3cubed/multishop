<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.products.behaviors.ProductBehavior");
Yii::import("common.modules.pages.models.PageSEOTrait");
/**
 * Description of ProductForm
 *
 * @author kwlok
 */
class ProductForm extends LanguageParentForm 
{
    use PageSEOTrait;
    /*
     * Inherited attributes
     */
    public $model = 'Product';
    /**
     * Controlled attributes
     * @see LanguageParentForm
     */
    protected $persistentAttributes = ['shippings','shop_id','code'];
    protected $childFormClass = 'ProductShippingForm';
    protected $childFormModelAttributes = ['product_id','shipping_id','surcharge'];
    protected $childFormAttribute = 'shippings';
    protected $childForm2Attribute = 'categories';
    /*
     * Local attributes
     */
    public $brand_id;
    public $code;
    public $name;
    public $image;
    public $unit_price;
    public $weight;
    public $spec;
    public $slug;
    public $status;
    public $create_time;
    public $shippings=[];    
    public $categories=[];
    /*
     * SEO attributes
     */
    public $seoTitle;
    public $seoKeywords;
    public $seoDesc;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'productbehavior' => [
                'class'=>'ProductBehavior',
            ],
        ]);
    }  
    /**
     * @return array Definitions of form attributes that requires multi languages
     */       
    public function localeAttributes()
    {
        return [
            'name'=>[
                'required'=>true,
                'inputType'=>'textField',
                'inputHtmlOptions'=>['size'=>80,'maxlength'=>100],
            ],
            'spec'=>[
                'required'=>false,
                'purify'=>true,
                'label'=>false,
                'inputType'=>'textArea',
                'inputHtmlOptions'=>['size'=>60,'rows'=>5],
                'ckeditor'=>[
                    'imageupload'=>true,
                    'js'=>'productckeditor.js',
                ],
            ],
        ];
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
        return array_merge(parent::rules(),$this->seoRules(),[
            ['code, name, unit_price', 'required'],
            ['image', 'required','message'=>Sii::t('sii','Please select primary image for this product')],
            ['brand_id, weight', 'numerical', 'integerOnly'=>true],
            ['code', 'length', 'max'=>6],
            ['code', 'ruleCompositeUniqueKey','errorMessage'=>Sii::t('sii','Code is already taken'),'on'=>'create'],
            ['name', 'length', 'max'=>100],
            ['unit_price, status', 'length', 'max'=>10],
            ['unit_price', 'length', 'max'=>10],
            ['unit_price', 'type', 'type'=>'float'],
            ['slug', 'length', 'max'=>100],
            ['slug', 'ruleSlugUnique','on'=>'create'],
            ['slug', 'ruleSlugWhitelist', 'on'=>'create'],

            //This column stored json encoded spec in different languages.
            ['spec', 'safe'],
            ['image', 'safe'],
            ['status', 'length', 'max'=>10],
            //business rules
            ['shippings', 'ruleShippings'],
            ['weight', 'ruleWeight'],
        ]);
    } 
    /**
     * Verify product url slug uniqueness
     */
    public function ruleSlugUnique($attribute,$params)
    {
        if (!empty($this->slug)){
            if (Product::model()->exists('slug=\''.$this->slug.'\''))
                $this->addError('slug',Sii::t('sii','Product URL "{slug}" is already taken.',['{slug}'=>$this->slug]));
        }
    }        
    /**
     * @see Product validation rules "CompositeUniqueKeyValidator"
     */
    public function ruleCompositeUniqueKey($attribute,$params)
    {
        $this->modelInstance->code = $this->code;
        $this->modelInstance->shop_id = $this->shop_id;
        $this->modelInstance->setScenario($this->getScenario());
        $this->modelInstance->validate(['code']);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }
    /**
     * @see Product::ruleWeight()
     */
    public function ruleWeight($attribute,$params)
    {
        $this->modelInstance->weight = $this->weight;
        $this->modelInstance->ruleWeight($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }
    /**
     * Validate rules of shippings
     */
    public function ruleShippings($attribute,$params)
    {
        $previousBase = null;
        foreach ($this->shippings as $shipping) {
            //logTrace(__METHOD__.' '. get_class($shipping).' attribute',$shipping->attributes);
            //validation [1]
            $shipping->validateLocaleAttributes();            
            //validation [2]
            $tier = ShippingTier::model()->findByAttributes(['shipping_id'=>$shipping->shipping_id]);
            if ($tier!=null){
                if ($previousBase===null)
                    $previousBase = $tier->base;
                if ($previousBase != $tier->base){
                    $this->addError('id',Sii::t('sii','Tiered Type Shipping Base must all be the same.'));
                    break;
                }
                $previousBase = $tier->base;
            }
            
            if ($shipping->hasErrors()){
                //logTraceDump(__METHOD__.' shipping errors',$shipping->getErrors());
                $this->addErrors($shipping->getErrors());
            }
        }//end for loop
    }           
    /**
     * Validate childforms
     */
    public function validateChildForm() 
    {
        $this->ruleShippings('shippings',[]);
        //no rules for categories validation
    }  
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge(parent::attributeToolTips(),$this->seoAttributeToolTips());
    }     
    /**
     * Return multi-lang attributes that inherited form specifically owns
     * @return type
     */
    public function getLocaleAttributes()
    {
        $attributes = parent::getLocaleAttributes();
        unset($attributes['seoTitle']);
        unset($attributes['seoKeywords']);
        unset($attributes['seoDesc']);
        return $attributes;
    }    
    /**
     * Validate attributes looping through value of each locale/language
     * It also make sure at least one locale (default) must exists
     * It further validates seo attributes                   
     * @see rules()
     * @return boolean
     */
    public function validateLocaleAttributes()
    {
        parent::validateLocaleAttributes();//do first round of validation
        foreach ($this->seoParams as $field => $value) {
            $this->validateLocaleAttribute($field,$value);//Validate individual param
        }
        return !$this->hasErrors();
    }       
    /**
     * @return model attributes to be copied
     */
    public function getModelAttributes()
    {
        $attributes = parent::getModelAttributes();
        unset($attributes[$this->childForm2Attribute]);
        return $attributes;
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
    /**
     * Transform inner form into inner models
     * @return \model
     */
    public function getCategoryModels()
    {
        $models = [];
        foreach ($this->categories as $key => $encodedCategory) {
            $category = explode('.', $encodedCategory);
            $model = new ProductCategory();
            $model->category_id = $category[0];
            if (isset( $category[1]))
                $model->subcategory_id = $category[1];
            logTrace(__METHOD__.' product category attributes',$model->attributes);
            $models[] = $model;
        }
        return $models;
    }    
    /**
     * Encode category keys
     * array(
     *  category_id1.subcategory_id1,
     *  category_id2.subcategory_id2,
     * )
     * @return array of encoded category keys 
     */
    public function getEncodedCategoryKeys()
    {
        $keys = [];
        foreach ($this->modelInstance->categories as $model) {
            $keys[] = $model->toKey();
        }
        return $keys;
    }
}
