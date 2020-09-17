<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ThemeFormTrait
 *
 * @author kwlok
 */
trait ThemeFormTrait
{
    /**
     * Local attributes
     */
    public $id;//The form id; It is also the underlying model id
    public $current = false;//True if to set to current theme
    public $installed = false;//True if installed or purchased before
    public $theme;
    public $style;
    /**
     * Sub form - Setting fields
     */
    public $cssForm;//CssSettingsForm 
    public $settingForm = [];//all the setting forms, dynamically loaded based on each theme settings (refer to settings_map.json)
    protected $group;//theme group
    protected $ownerModelClass;//theme owner model class name, e.g. ShopTheme
    protected $ownerAttribute;//theme owner attribute name, e.g. shop_id
    private $_t;//theme model instance
    private $_m;//owner model instance
    /**
     * Constructor.
     */
    protected function themeConstructor($theme, $style, $scenario='')
    {
        logTrace(__METHOD__.' Theme: '.$theme.' , style: '.$style);
        if ($scenario=='update') {
            $this->prepareUpdate($theme, $style);
        }
        else {
            $this->prepareView($theme, $style);
        }
        parent::__construct($scenario);
    }        
    /**
     * Declares the validation rules.
     */
    public function themeRules()
    {
        return [
            ['theme, style', 'required'],
            ['theme, style', 'length', 'max'=>25],
        ];
    }    
    /**
     * Declares attribute labels.
     */
    public function themeAttributeLabels()
    {
        $class = $this->ownerModelClass;
        return array_merge($class::model()->attributeLabels(),[
            'theme' => Sii::t('sii','Theme'),
            'style' => Sii::t('sii','Theme Style'),
            'publish'=>Sii::t('sii','Publish as live theme'),
            'currentTheme'=>Sii::t('sii','Current theme'),
            'css'=>Sii::t('sii','CSS Script'),
        ]);
    }
    
    public function getDataProvider()
    {
        //todo More conditions? select theme based on subscribed plan or price
        return Tii::searchThemes($this->group);
    }  
    
    protected function setModel($model)
    {
        $this->_m = $model;
    }
    /**
     * Return owner model
     * If $this->id has value, it try load underlying model from db
     * @return CModel
     */
    public function getModel() 
    {
        if (isset($this->id)){
            if (!isset($this->_m)){
                $class = $this->ownerModelClass;
                $this->_m = $class::model()->findByPk($this->id);
            }
            return $this->_m;
        }
        else 
            return null;
    }   
    
    public function getThemeModel()
    {
        if (!isset($this->_t)){
            $this->_t = Theme::model()->locateTheme($this->theme)->find();//note that theme name is unique, thats why here we do not filter more by theme group
            if ($this->_t==null)
                throw new CException('Theme not found');
        }
        return $this->_t;
    }
    
    public function getThemeName($locale)
    {
        return $this->themeModel->localeName($locale);
    }    
    
    public function getStyleName($locale)
    {
        return $this->themeModel->getStyle($this->style)->getName($locale);
    }    
    
    public function getPrice()
    {
        return $this->themeModel->formatPrice();
    }    
    
    public function getDesigner()
    {
        return $this->themeModel->designer;
    }    
    /**
     * @return array customized attribute tooltips (name=>tooltip)
     */
    public function attributeToolTips()
    {
        $class = $this->ownerModelClass;
        return $class::model()->attributeToolTips();
    }
    
    public function displayName() 
    {
        $class = $this->ownerModelClass;
        return $class::model()->displayName();
    }
    /**
     * Owner theme record not yet created means payment not yet done
     * @return boolean
     */
    public function getPaymentRequired()
    {
        return $this->model==null && $this->themeModel->price > 0;
    }

    public function getStatusText()
    {
        if ($this->current)
            return Helper::htmlColorText(['text'=>Sii::t('sii','Live'),'color'=>'red']);
        else
            return '';
    }
    
    public function getDescription()
    {
        if ($this->installed)
            return Sii::t('sii','Edit and preview shop theme. Make sure that all are looking good before "Save".');
        else
            return Sii::t('sii','You are now installing this new theme / style. Click "Install" to complete the process.');
    }
    /**
     * Set attributes ready for view
     * @param type $theme
     * @param type $style
     */
    protected function prepareView($theme,$style)
    {
        $ownerClass = $this->ownerModelClass;
        $ownerId = $this->{$this->ownerAttribute};
        if ($ownerClass::model()->locateOwner($ownerId)->count()==0){
            //Do not have owner theme record yet, auto create the default theme record
            $themeModel = $ownerClass::create($ownerId, Tii::defaultTheme($this->group), Tii::defaultStyle(),Process::THEME_ONLINE);//the first owner theme, so status is ONLINE!
        }

        $themeModel = $ownerClass::model()->locateOwner($ownerId)->locateTheme($theme,$style)->find();
        if ($themeModel==null){
            //owner theme model not found, use input theme and style
            $this->theme = $theme;
            $this->style = $style;
        }
        else {
            //owner theme model found
            $this->assignAttributes($themeModel);
            $this->setCurrentTheme($themeModel, $style);
        }
    }    
    /**
     * Set attributes ready for update
     * @param type $theme
     * @param type $style
     */
    protected function prepareUpdate($theme,$style)
    {
        $ownerClass = $this->ownerModelClass;
        $ownerId = $this->{$this->ownerAttribute};
        $themeModel = $ownerClass::model()->locateOwner($ownerId)->locateTheme($theme,$style)->find();
        if ($themeModel==null){
            //Auto create owner theme model if not found
            //Each theme and style pair should have one record by itself
            $themeModel = $ownerClass::create($ownerId, $theme, $style);//subsequent owner theme is default OFFLINE until user save it as ONLINE
        }
        $this->assignAttributes($themeModel);
        $this->setCurrentTheme($themeModel, $style);
    }
    
    protected function assignAttributes($themeModel)
    {
        $this->setModel($themeModel);
        $this->id = $themeModel->id;
        $this->installed = true;
        $this->theme = $themeModel->theme;
        $this->style = $themeModel->style;
        $this->cssForm->css = $themeModel->getParam('css');
        //Load setting form with local values
        foreach ($themeModel->model->getSettingsMap() as $id => $setting) {//getting setting map from Theme model
            //logTrace(__METHOD__.' Traverse setting map '.$id,$setting);
            if ($themeModel->getParam('settings')!=null){
                foreach ($themeModel->getParam('settings') as $data) {//retrieve shop theme local settings values
                    foreach ($data as $key => $value) {
                        //loop through map to find the target field
                        foreach ($setting['fields'] as $i => $field) {
                            if ($field['id']==$key){
                                $setting['fields'][$i]['value'] = $value;
                                //logTrace(__METHOD__.' Load back '.$key.' ',$value);
                                break;//when found
                            }
                        }
                    }
                }                
            }
            $themeSettingForm = $this->ownerModelClass.'SettingForm';
            $form = new $themeSettingForm();//e.g. ShopThemeSettingForm
            $form->id = $id;
            $form->name = $setting['name'];
            $form->fields = $setting['fields'];
            $this->settingForm[] = $form;
        }
        //logTrace(__METHOD__,$this->attributes);
    }    
    
    protected function setCurrentTheme($themeModel,$inputStyle)
    {
        $this->current = $themeModel->online() && $themeModel->style==$inputStyle;//set to true if this is current theme
    }
        
}
