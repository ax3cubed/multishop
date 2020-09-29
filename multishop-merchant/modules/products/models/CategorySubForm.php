<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.schildform.behaviors.SChildFormBehavior');
Yii::import("common.modules.products.behaviors.CategorySubBehavior");
/**
 * Description of CategorySubForm
 *
 * @author kwlok
 */
class CategorySubForm extends LanguageChildForm 
{
    private $_c;//category model instance
    /*
     * Inherited attributes
     */
    public $model = 'CategorySub';
    /**
     * Controlled attributes
     * @see LanguageChildForm
     */
    public    $keyAttribute = 'shop_id';    
    protected $exclusionAttributes = array('account_id');//shop_id is included
    protected $persistentAttributes = array('category_id','id');
    /*
     * Local attributes
     */
    public $category_id;
    public $name;
    public $slug;
    public $create_time;   
    public $update_time;   
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
                'deleteScriptName'=>'removesubcategory',
                'hiddenAttributes'=>array('id','category_id'),
                'nonLocaleAttributes'=>array(
                    'slug.dummy'=>array(
                        'htmlField'=>false,
                        'rawValueCallback'=>'getSlugHtmlField',
                    ),
                ),
            ),
            'categorysubbehavior' => array(
                'class'=>'CategorySubBehavior',
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
                'inputHtmlOptions'=>array('size'=>30,'maxlength'=>50),
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
            array('category_id, name', 'required'),
            array('id, category_id', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>100),
            array('name', 'ruleExists'),
            array('slug', 'length', 'max'=>100),
            array('slug', 'ruleSlugUnique','on'=>$this->getCreateScenario()),
            array('slug', 'ruleSlugWhitelist', 'on'=>$this->getCreateScenario()),
            array('create_time, update_time', 'safe'),
        );
    }     
    /**
     * Validate if attribute exisits
     */
    public function ruleExists($attribute,$params)
    {
        $this->modelInstance->attributes = $this->getAttributes($this->persistentAttributes);
        $this->modelInstance->name = $this->name;
        $this->modelInstance->setScenario($this->getScenario());
        $this->modelInstance->ruleExists($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }        
    /**
     * Verify category url slug uniqueness
     */
    public function ruleSlugUnique($attribute,$params)
    {
        $this->modelInstance->attributes = $this->getAttributes($this->persistentAttributes);
        $this->modelInstance->slug = $this->slug;
        $this->modelInstance->setScenario($this->getScenario());
        $this->modelInstance->ruleSlugUnique($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }     
    /**
     * Return category model
     * If $this->category_id has value, it try load underlying model from db
     * @return CModel
     */
    public function getCategory() 
    {
        if (isset($this->category_id)){
            if (!isset($this->_c))
                $this->_c = Category::model()->findByPk($this->category_id);
            return $this->_c;
        }
        else 
            return null;
    }  
    /**
     * Return slug html field
     * @return type
     */
    public function getSlugHtmlField()
    {
        $html = CHtml::openTag('p',array('class'=>'note'));
        $html .= CHtml::tag('span',array('class'=>'category-url'),CHtml::encode('<'.Sii::t('sii','Above {url}',array('{url}'=>Category::model()->getAttributeLabel('slug'))).'>/'));
        $html .= CHtml::activeTextField($this,'slug',array('name'=>$this->formatAttributeName(null,'slug'),'size'=>40,'maxlength'=>100,'disabled'=>$this->isSkipSlugScenario,'class'=>$this->isSkipSlugScenario?'disabled ':'enabled '.($this->hasErrors($this->formatErrorName(null,'slug'))?'error':''))); 
        if ($this->isSkipSlugScenario)//since will be disabled, show hidden field
            $html .= CHtml::activeHiddenField($this,'slug',array('name'=>$this->formatAttributeName(null,'slug'))); 
        $html .= CHtml::closeTag('p');
        return $html;
    }
 
}
