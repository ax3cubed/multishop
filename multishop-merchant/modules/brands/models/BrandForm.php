<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.brands.behaviors.BrandBehavior");
/**
 * Description of BrandForm
 *
 * @author kwlok
 */
class BrandForm extends LanguageForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'Brand';
    /*
     * Local attributes
     */
    public $name;
    public $image;
    public $description;
    public $slug;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'brandbehavior' => array(
                'class'=>'BrandBehavior',
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
            'description'=>array(
                'required'=>false,
                'inputType'=>'textArea',
                'inputHtmlOptions'=>array('size'=>60,'rows'=>5),
                'ckeditor'=>array(
                    'js'=>'brandckeditor.js',
                ),
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
            array('description', 'length', 'max'=>5000),
            array('description', 'safe'),
            array('image', 'numerical', 'integerOnly'=>true),
            array('image', 'safe'),
            array('slug', 'length', 'max'=>100),
            array('slug', 'ruleSlugUnique','on'=>$this->getCreateScenario()),
            array('slug', 'ruleSlugWhitelist', 'on'=>$this->getCreateScenario()),
        ));
    }
    /**
     * Verify brand url slug uniqueness
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
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge(parent::attributeToolTips(),array(
            'slug'=>Sii::t('sii','This is the brand\'s SEO url. If you leave this field blank, it will be auto-generated based on brand name above.'),
        ));
    }

}
