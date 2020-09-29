<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopApplicationForm
 *
 * @author kwlok
 */
class ShopApplicationForm extends SFormModel 
{
    public $id;//has value when shop is applied successfully
    public $name;
    public $slug;
    public $contact_person, $contact_no, $email;
    public $timezone, $language, $currency, $weight_unit;
    public $applyUrl;
    public $formView;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, slug, contact_person, contact_no, email, timezone, language, currency, weight_unit', 'required'),
            array('name, slug', 'length', 'max'=>50),
            array('contact_person', 'length', 'max'=>32),
            array('contact_no', 'length', 'max'=>20),
            array('contact_no', 'numerical', 'min'=>8, 'integerOnly'=>true),
            array('email', 'length', 'max'=>100),
            array('email', 'email'),
            array('name', 'ruleNameUnique'),
            array('slug', 'ruleSlugUnique'),
            array('slug', 'ruleSlugWhitelist'),
            array('currency, weight_unit', 'length', 'max'=>3),
            array('timezone, language', 'length', 'max'=>20),
        );
    }
    /**
     * Verify shop name uniqueness
     */
    public function ruleNameUnique($attribute,$params)
    {
        $testSample  = '"'.$this->language.'":'.json_encode($this->name);
        $criteria = new CDbCriteria();
        $criteria->compare('name',$testSample,true,'AND',true);
        logTrace(__METHOD__,$criteria);
        $count = Shop::model()->count($criteria);
        if ($count>=1)                
            $this->addError('name',Sii::t('sii','Shop name is already taken.'));
    }        
    /**
     * Verify shop url slug uniqueness
     */
    public function ruleSlugUnique($attribute,$params)
    {
        if (Shop::model()->exists('slug=\''.$this->slug.'\''))
            $this->addError('slug',Sii::t('sii','Shop URL is already taken.'));
    }        
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => Sii::t('sii','Shop Name'),
            'slug' => Sii::t('sii','Shop URL'),
            'contact_person' => Sii::t('sii','Contact Person'),
            'contact_no' => Sii::t('sii','Contact No'),
            'email' => Sii::t('sii','Email'),
            'timezone' => Sii::t('sii','Time Zone'),
            'language' => Sii::t('sii','Default Language'),
            'currency' => Sii::t('sii','Accepted Currency'),
            'weight_unit' => Sii::t('sii','Weight Unit'),
        );
    }  
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return Shop::model()->attributeToolTips();
    }  
    
    public function getBaseUrl()
    {
        return Shop::model()->baseUrl;
    }

    public function displayName() 
    {
        return Shop::model()->displayName();
    }

    public function getViewUrl()
    {
        return Shop::model()->findByPk($this->id)->viewUrl;
    }    
}