<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shops.models.ThemeFormTrait');
Yii::import('shops.models.ShopThemeSettingForm');
/**
 * Description of ShopThemeForm
 *
 * @author kwlok
 */
class ShopThemeForm extends SFormModel 
{
    use ThemeFormTrait;
    
    private $_s;//shop instance
    public $shop_id;
    /**
     * Constructor.
     */
    public function __construct($shopModel, $theme, $style, $scenario='')
    {
        $this->group = Tii::GROUP_SHOP;
        $this->ownerModelClass = 'ShopTheme';
        $this->ownerAttribute = 'shop_id';
        $this->_s = $shopModel;
        $this->shop_id = $shopModel->id;
        $this->cssForm = new CssSettingsForm();
        $this->cssForm->shop_id = $this->_s->id;
        $this->themeConstructor($theme, $style, $scenario);
    }       
    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array_merge($this->themeRules(),[
            ['shop_id', 'required'],
        ]);
   }    
    
    public function getShop()
    {
        return $this->_s;
    }
    
    public function getPreviewUrl()
    {
        return $this->themeModel->getPreviewUrl($this->shop,$this->style);
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return $this->themeAttributeLabels();
    }
    /**
     * Check if user has CSS editor
     * This is linked back to shop subscription
     * @return bool
     */
    public function hasCSSEditor()
    {
        return Subscription::apiHasService(Feature::getKey(Feature::$hasCSSEditing), ['shop'=>$this->shop_id]);
    }    
    
}
