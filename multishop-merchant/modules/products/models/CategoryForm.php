<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.products.behaviors.CategoryBehavior");
/**
 * Description of CategoryForm
 *
 * @author kwlok
 */
class CategoryForm extends LanguageParentForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'Category';
    /**
     * Controlled attributes
     * @see LanguageParentForm
     */
    protected $persistentAttributes = array('subcategories','shop_id','id');
    protected $childFormKeyAttribute = 'shop_id';
    protected $childFormClass = 'CategorySubForm';
    protected $childFormAttribute = 'subcategories';
    protected $childFormModelAttributes = array('category','name','id','slug');
    /*
     * Local attributes
     */
    public $name;
    public $image;
    public $slug;
    public $subcategories=array();     
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'categorybehavior' => array(
                'class'=>'CategoryBehavior',
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
                'inputHtmlOptions'=>array('size'=>100,'maxlength'=>100),
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
            array('name', 'required'),
            array('name', 'length', 'max'=>100),
            array('name', 'ruleExists'),
            array('image', 'numerical', 'integerOnly'=>true),
            array('image', 'safe'),
            array('slug', 'length', 'max'=>100),
            array('slug', 'ruleSlugUnique','on'=>$this->getCreateScenario()),
            array('slug', 'ruleSlugWhitelist', 'on'=>$this->getCreateScenario()),
            //validate subcategories 
            array('subcategories', 'ruleSubcategories'),
        ));
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),array(
            'subcategory'=>Sii::t('sii','Subcategory')
        ));        
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge(parent::attributeToolTips(),array(
            'slug'=>Sii::t('sii','This is the category\'s SEO url. If you leave this field blank, it will be auto-generated based on category name.'),
            'subcategory'=>Sii::t('sii','Here you can add subcategories to the category. For each of their SEO urls, if you leave them blank, they will be auto-generated based on subcategory name.'),
        ));
    }
    /**
     * Validate options
     */
    public function validateChildForm() 
    {
        $this->ruleSubcategories('subcategories',array());
    } 
    /**
     * Validate if attribute exisits
     */
    public function ruleExists($attribute,$params)
    {
        $this->modelInstance->attributes = $this->getAttributes($this->persistentAttributes);
        $this->modelInstance->name = $this->name;
        $this->modelInstance->ruleExists($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }            
    /**
     * Verify category url slug uniqueness
     */
    public function ruleSlugUnique($attribute,$params)
    {
        $this->modelInstance->slug = $this->slug;
        $this->modelInstance->shop_id = $this->shop_id;
        $this->modelInstance->setScenario($this->getScenario());
        $this->modelInstance->ruleSlugUnique($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }        
    /**
     * Validate rules of subcategories
     * [1] subcategory inherent validation (@see CategorySubForm)
     * [2] subcategory slugs must be unique
     */
    public function ruleSubcategories($attribute,$params)
    {
        $slugs = [];
        foreach ($this->subcategories as $subcategory) {
            logTrace(__METHOD__.' '. get_class($subcategory).' attribute',$subcategory->attributes);
            $subcategory->validateLocaleAttributes();
            //check every subcategory name that is not empty submitted must be unique
            if (!empty($subcategory->slug) && in_array($subcategory->slug,$slugs)){
                $subcategory->addError($subcategory->formatErrorName(null,'slug'),Sii::t('sii','Subcategory URL "{slug}" is already taken.',array('{slug}'=>$subcategory->slug)));
            }
            //recording all previous slug
            $slugs[] = $subcategory->slug;
            if ($subcategory->hasErrors()){
                logTraceDump(__METHOD__.' subcategory errors',$subcategory->getErrors());
                $this->addErrors($subcategory->getErrors());
            }
        }//end for loop
    }    
    /**
     * OVERRIDDEN
     * Instantiate child form
     * @param type $data
     * @param type $skipValidationAttribute attribute to skip validation (assigned with temp value)
     * @return \childFormClass
     */    
    public function instantiateChildForm($data,$skipValidationAttribute=null)
    {
        $childform = parent::instantiateChildForm($data, $skipValidationAttribute);
        $childform->setScenario($this->getCreateScenario());//set to "create" scenario 
        logTrace(__METHOD__.' parent scenario = "'.$this->getScenario().'" , child form scenario "'.$childform->getScenario().'"');
        return $childform;
    }

}
