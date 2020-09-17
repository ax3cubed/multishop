<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('themes.controllers.ThemeViewControllerTrait');
/**
 * Description of DesignController
 *
 * @author kwlok
 */
class DesignController extends ShopParentController 
{
    use ThemeViewControllerTrait;
    
    public function init()
    {
        parent::init();
        $this->getModule()->registerScripts();
        $this->modelType = 'ShopThemeForm';
        $this->viewName =  Sii::t('sii','Themes Management');
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->loadSessionParentShop();
    }
    /**
     * Show shop design form.
     */
    public function actionIndex()
    {
        $shopModel = $this->findShop();

        $this->pageTitle = Sii::t('sii','Themes Management').' | '.$shopModel->parseName(user()->getLocale());

        $form = new $this->modelType($shopModel,$shopModel->getTheme(),$shopModel->getThemeStyle());
        
        $this->render('index',['form'=>$form]);
    }     

    public function actionUpdate()
    {
        if (isset($_POST[$this->modelType]['shop_id']) && 
            isset($_POST[$this->modelType]['theme']) &&
            isset($_POST[$this->modelType]['style'])) { //All mandatory ShopThemeForm fields must present

            $form = new $this->modelType($this->findShop($_POST[$this->modelType]['shop_id']),$_POST[$this->modelType]['theme'],$_POST[$this->modelType]['style'],'update');
            $form->model->attributes = $form->attributes;
            logTrace(__METHOD__.' theme model attributes',$form->model->attributes);            
            
            $form->current = isset($_POST[$this->modelType]['current'])?$_POST[$this->modelType]['current']:false;
            $params = [];//Extra theme params placeholder
            //Capture CSS setting if any
            if (isset($_POST['CssSettingsForm'])){
                $form->cssForm->shop_id = $form->shop_id;
                $form->cssForm->attributes = $_POST['CssSettingsForm'];
                $params['css'] = $form->cssForm->css;
            }
            //Capture dynamic setting if any
            if (isset($_POST['ShopThemeSettingForm']) && is_array($_POST['ShopThemeSettingForm'])){
                $params['settings'] = $_POST['ShopThemeSettingForm'];
                //logTrace(__METHOD__.' Shop theme settings ', $params['settings']);
            }
            
            try {
                $this->module->serviceManager->updateTheme(user()->getId(),$form->model,$form->current,$params);
                //Reload form to have latest value
                $form = new $this->modelType($this->findShop($_POST[$this->modelType]['shop_id']),$_POST[$this->modelType]['theme'],$_POST[$this->modelType]['style'],'update');
                user()->setFlash(get_class($form),[
                    'message'=>Sii::t('sii','{model} is saved successfully.',['{model}'=>$form->model->displayName()]),
                    'type'=>'success',
                    'title'=>Sii::t('sii','{model} Update',['{model}'=>$form->model->displayName()]),
                ]);
                unset($_POST);
                
            } catch (CException $e) {
                logError(__METHOD__.' '.$this->modelType.' update error',$form->model->getErrors());
                user()->setFlash(get_class($form),[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','{model} Error',['{model}'=>$form->model->displayName()]),
                ]);
            }
        }
        else {//GET request
            
            if (!isset($_GET['theme']) && !isset($_GET['style'])){
                throwError404(Sii::t('sii','Theme not found'));
            }
            
            $form = new $this->modelType($this->findShop(),$_GET['theme'],$_GET['style']);
        }
        
        $this->pageTitle = Sii::t('sii','Themes Management').' | '.$form->shop->parseName(user()->getLocale());
        
        
        if ($form->paymentRequired){
            $this->redirect('/themes/buy/'.$form->theme.'?shop='.$form->shop->id);
        }
        else {
            $this->render('update',['form'=>$form]);
        }
    }
    
    protected function findShop($shopId=null)
    {
        if (isset($shopId)){
            $model = Shop::model()->findByPk($shopId);
            if ($model===null)
                throw new CException(Sii::t('sii','Shop not found'));
        }
        elseif (!$this->hasParentShop()){
            logTrace(__METHOD__.' $_GET', $_GET);
            $search = current(array_keys($_GET));//take the first key as search attribute: shop slug
            $model = Shop::model()->findByAttributes(['slug'=>$search]);
            if (!isset($search))
                $model = $this->getCurrentShop();
            if ($model===null)
                throw new CException(Sii::t('sii','Current shop not found'));
        }
        else
            $model = $this->getParentShop();
        
        return $model;
    }
    
    protected function getCurrentShop()
    {
        return $this->loadModel(SActiveSession::get($this->shopStateVariable),'Shop');
    }
    /**
     * Theme preview url; Using shop home page as the entry page 
     * Theme style is using placeholder {style} as at the moment style is unknown until user select which style to preview
     * @see shops.js enablethemebuttons() for {style} parsing
     * 
     * @param type $owner
     * @param type $theme
     * @return string
     */
    public function getThemePreviewUrl($owner,$theme)
    {
        return $theme->getPreviewUrl($owner,'{style}',ShopPage::HOME);
    }
    
    public function getThemeUpdateUrl($shop,$params=[])
    {
        $url = url('shop/themes/update/'.$shop->slug);
        if (!empty($params))
            $url .= '?'.http_build_query($params);
        return $url;
    }
    
    public function getThemeButtonLabel(Shop $shop,Theme $theme)
    {
        $btn = ['current'=>false,'label'=>Sii::t('sii','Install'),'cssClass'=>'install'];
        $current = $theme->theme==$shop->getTheme();
        $owned = $shop->hasTheme($theme->theme);
        if ($current){
            $btn['label'] = Sii::t('sii','Current');
            $btn['cssClass'] = 'current';
            $btn['current'] = true;
        }
        elseif ($owned){
            $btn['label'] = Sii::t('sii','Choose');
            $btn['cssClass'] = 'publish';
        }
        elseif (!$owned && $theme->price > 0){
            $btn['label'] = Sii::t('sii','Buy');
            $btn['cssClass'] = 'buy';
        }
        return $btn;
    }
    
    protected function getSectionsData($model,$locale) 
    {
        $sections = new CList();
        $count = 0;
        foreach ($model->settingForm as $form) {
            $sections->add([
                'id'=>'setting_form_'.$form->id,
                'name'=>$form->getName($locale),
                'heading'=>true,
                'top'=>$count==0?true:false,
                'viewFile'=>'_form_template','viewData'=>['content'=>$form->render($locale)],
            ]);
            $count++;
        }

        if ($model->hasCSSEditor()) {
            $sections->add([
                'id'=>'css',
                'name'=>$model->getAttributeLabel('css'),
                'heading'=>true,
                'viewFile'=>'_form_template','viewData'=>['content'=>$model->cssForm->renderForm($this,false),'overview'=>$model->getToolTip('css')],
            ]);
        }
        return $sections->toArray();
    }      
}