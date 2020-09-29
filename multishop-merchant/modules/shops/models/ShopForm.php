<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.shops.behaviors.ShopBehavior");
Yii::import('common.modules.shops.models.BrandSettingsForm');
/**
 * Description of ShopForm
 *
 * @author kwlok
 */
class ShopForm extends LanguageMasterForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'Shop';
    protected $slaveFormAttribute = 'addressForm';
    protected $persistentAttributes = ['id','slug','status'];
    /*
     * Local attributes
     */
    public $name;
    public $tagline;
    public $slug;
    public $image;
    public $category;
    public $contact_person, $contact_no, $email;
    public $timezone, $language, $currency, $weight_unit; 
    public $status;
    public $addressForm;//ShopAddressForm
    /**
     * Initializes this form.
     */
    public function init()
    {
        parent::init();
        //Shop form does not have these inherited attributes
        $this->setAttributeExclusion(['shop_id']); 
    }  
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'locale' => [
                'class'=>'common.components.behaviors.LocaleBehavior',
                'ownerParent'=>'self',
            ],
            'transition' => [
                'class'=>'common.components.behaviors.TransitionBehavior',
                'activeStatus'=>Process::SHOP_ONLINE,
                'inactiveStatus'=>Process::SHOP_OFFLINE,
            ],         
            'shopbehavior' => [
                'class'=>'ShopBehavior',
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
                'inputHtmlOptions'=>['size'=>75,'maxlength'=>50],
            ],
            'tagline'=>[
                'required'=>false,
                'inputType'=>'textField',
                'inputHtmlOptions'=>['size'=>75,'maxlength'=>50],
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
        return [
            ['account_id, name, contact_person, contact_no, email, timezone, language, currency, weight_unit, category', 'required'],
            ['account_id, image, category', 'numerical', 'integerOnly'=>true],
            ['name, tagline', 'length', 'max'=>50],
            ['image', 'ruleImage'],
            ['name', 'ruleNameUnique'],
            ['slug', 'ruleSlugUnique'],
            ['slug', 'ruleSlugAsSubdomain'],
            ['slug', 'ruleSlugWhitelist'],
            ['email', 'length', 'max'=>100],
            ['category', 'length', 'max'=>8],
            ['contact_person', 'length', 'max'=>32],
            ['contact_no', 'numerical', 'min'=>8, 'integerOnly'=>true],
            ['email', 'email'],
            ['currency, weight_unit', 'length', 'max'=>3],
            ['timezone, language, contact_no', 'length', 'max'=>20],            
            ['image, slug', 'safe'],
        ];
    }
    /**
     * Check if shop image is uploaded
     * @param type $attribute
     * @param type $params
     */
    public function ruleImage($attribute,$params)
    {
        if ($this->image==null && !$this->modelInstance->hasSessionImages())
            $this->addError('image',Sii::t('sii','Please upload logo for this shop'));
    }
    /**
     * Check if shop name is unique
     */
    public function ruleNameUnique($attribute,$params)
    {
        $model = Shop::model()->findByPk($this->id);
        if ($model===null)
            throw new CException(Sii::t('sii','Shop not found'));
        
        $currentLocale = $this->getCurrentLocale();
        $existingName = Helper::getUTF8encodedChars($model->getLanguageValue('name',$currentLocale));
        logTrace(__METHOD__." comparing existing: $currentLocale.$existingName vs new: $currentLocale.$this->name ...");
        if ($existingName!=$this->name){//uniqueness check only when name has diff from existing one
            $testSample  = '"'.$currentLocale.'":'.json_encode($this->name);
            $criteria = new CDbCriteria();
            $criteria->compare('name',$testSample,true,'AND',true);
            logTrace(__METHOD__,$criteria);
            $count = Shop::model()->count($criteria);
            if ($count>=1)                
                $this->addError('name',Sii::t('sii','Shop name is already taken.'));
        }
    }     
    /**
     * Verify shop url slug uniqueness
     */
    public function ruleSlugUnique($attribute,$params)
    {
        if ($this->prototype()){//only check when is in prototype status - since slug can be edited here
            if (empty($this->slug))
                $this->addError('slug',Sii::t('sii','Please specify shop url.'));
            if (Shop::model()->exists('slug=\''.$this->slug.'\''))
                $this->addError('slug',Sii::t('sii','Shop URL is already taken.'));
        }
    }        
    /**
     * Verify shop slug is complying with the subdomain validation rules
     * Here we will be making slug = subdomain 
     */
    public function ruleSlugAsSubdomain($attribute,$params)
    {
        if ($this->prototype()){//only check when is in prototype status - since slug can be edited here
            $brandForm = new BrandSettingsForm();
            $brandForm->shop_id = $this->id;
            $brandForm->customDomain = $this->slug;
            if (!$brandForm->validate(['customDomain']))
                $this->addError('slug',$brandForm->getError('customDomain'));
        }
    }        
    /**
     * Overridden method
     * @return multi-lang attributes that this form specifically owns
     */
    public function getLocaleAttributes()
    {
        $attributes = $this->getAttributes();
        unset($attributes['model']);
        foreach ($this->getAttributeExclusion() as $exclude) {
            unset($attributes[$exclude]);
        }
        return $attributes;
    } 
    /**
     * Load locale attributes from model instance 
     * @param array $exclude attributes to be excluded from loading
     */
    public function loadLocaleAttributes($exclude=[])
    {
        foreach ($this->getLocaleAttributeKeys() as $attribute) {
            if (in_array($attribute, $exclude)==false)
                $this->$attribute = $this->modelInstance->$attribute;
        }
        //for shop prototype, all shop attributes set to empty
        if ($this->prototype()){
            $prototype = new ShopPrototypeForm($this->account_id,'update');
            $this->attributes = $prototype->getAttributes([
                    'name','slug','contact_person', 'contact_no', 'email',
                    'timezone','language','currency','weight_unit',
                ]);     
        }
        $this->checkIsNewRecord();
    }    
    /**
     * Load address form instance; Create one if not exists
     * @return type
     */
    public function loadAddressForm()
    {
        if ($this->addressForm==null)
            $this->addressForm = new ShopAddressForm($this->id);
        return $this->addressForm;
    }    
    /**
     * Load address form attributes from model instance
     */
    public function loadAddressFormAttributes()
    {
        $this->addressForm = $this->loadAddressForm();
        if (isset($this->modelInstance->address))
            $this->addressForm->attributes = $this->modelInstance->address->attributes;
        //logTraceDump(__METHOD__.' '.get_class($this),$this->attributes);
        return $this->addressForm;
    }   
    /**
     * Set address form attributes 
     * @param type $attributes Attributes to be set
     */
    public function setAddressFormAttributes($attributes,$json=false)
    {
        $this->addressForm = $this->loadAddressForm();
        $this->addressForm->assignLocaleAttributes($attributes,$json);
        //logTrace(__METHOD__.' '.get_class($this->addressForm),$this->addressForm->attributes);  
    }    
    /**
     * Set model attributes
     * @return \ShopForm
     */
    public function setModelAttributes()
    {
        $this->modelInstance->attributes = $this->getModelAttributes();
        if ($this->addressForm->hasAddress()){
            if (!$this->modelInstance->hasAddress())
                $this->modelInstance->address = new ShopAddress();
            $this->modelInstance->address->attributes = $this->addressForm->getModelAttributes();
        }
        //for shop prototype, slug can be set; so have to slug the value properly
        if ($this->prototype()){
            logTrace(__METHOD__.' calling simple slug...');
            $this->modelInstance->slug = $this->modelInstance->simpleSlug($this->slug);
        }
        logTrace(__METHOD__.' '.get_class($this->modelInstance), $this->modelInstance->attributes);
        return $this;
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
    /**
     * Overridden
     * For prototype shop, set attribute name value to null
     * @see LanguageForm::renderForm()
     */
    public function renderForm($controller,$readonly=false,$attributes=[],$return=false)
    {
        logTrace(__METHOD__.' before name attr value= '.$this->name);
        //for shop prototype, all shop attributes set to empty
        if ($this->prototype() && empty($this->name)){
            $prototype = new ShopPrototypeForm($this->account_id);
            $this->name = $prototype->name;     
        }
        logTrace(__METHOD__.' after name attr value= '.$this->name);
        return parent::renderForm($controller, $readonly, $attributes, $return);
    }    
    /**
     * @param type $color
     * @return string status text
     */
    public function getStatusText($color=true)
    {
        return $this->modelInstance->getStatusText($color);
    }    
    
    public function updatable()
    {
        return $this->modelInstance->updatable();
    }

    public function deletable()
    {
        return $this->modelInstance->deletable();
    }     
    
    public function hasAddress()
    {
        return $this->modelInstance->hasAddress();
    }

    public function getCustomDomain()
    {
        return $this->modelInstance->customDomain;
    }
    
    public function invokeSlaveFormValidation() 
    {
        return $this->addressForm->hasData();
    }
    
    public function parseName($locale)
    {
        return $this->modelInstance->parseName($locale);
    }    

}
