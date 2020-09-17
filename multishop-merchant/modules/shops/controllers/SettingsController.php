<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.SettingsControllerTrait');
Yii::import('common.modules.shops.controllers.ShopSettingsControllerTrait');
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
/**
 * Description of SettingsController
 *
 * @author kwlok
 */
class SettingsController extends ShopParentController 
{
    use SettingsControllerTrait, ImageControllerTrait;
    
    public function init()
    {
        parent::init();
        $this->getModule()->registerScripts();
        $this->modelType = 'ShopSetting';
        $this->faviconStateVariable = SActiveSession::SHOP_FAVICON;
        //-----------------
        // @see ImageControllerTrait
        $this->setSessionActionsExclude([
            $this->faviconUploadAction, 
            $this->faviconMediaGalleryFormGetAction,
            $this->faviconMediaGallerySelectAction,            
        ]);
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = false;
        $this->loadSessionParentShop();
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->serviceNotAvailableJsonAction,
            $this->faviconUploadAction, 
            $this->faviconMediaGalleryFormGetAction,
            $this->faviconMediaGallerySelectAction,            
        ];
        //-----------------//        
    }   
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action)
    {
        return $this->runBeforeAction($action);
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),$this->faviconActions(),[
            $this->serviceNotAvailableJsonAction => [
                'class'=>'common.modules.plans.actions.ServiceNotAvailableJsonAction',
                'postModel'=>'Subscription',
                'postField'=>'service',
                'flashId'=>'Shop',
            ],         
        ]);
    }   
    /**
     * A direct url to view a particular setting page without session shop
     * @param type $shop The shop slug
     * Expect a $_GET['setting'] 
     * Example: https://<domain>/shops/settings/view/<shop_slug>?setting=<setting>
     */
    public function actionView()
    {
        $shopModel = $this->getOwnerModel();//to load shop into session
        if (isset($_GET['setting'])){
            $this->redirect(url('shop/settings/'.strtolower($_GET['setting'])));
        }
    }
    /**
     * View all shop settings.
     */
    public function actionIndex()
    {
        $shopModel = $this->getOwnerModel();

        $this->pageTitle = Sii::t('sii','Shop Settings').' | '.$shopModel->displayLanguageValue('name',user()->getLocale());

        $this->render('index',['shopModel'=>$shopModel]);
    }     
    /**
     * Display checkout settings form.
     */
    public function actionCheckout()
    {
        $this->_actionInternal(new CheckoutSettingsForm(), ShopSetting::$checkout);
    }     
    /**
     * Display orders settings form.
     */
    public function actionOrders()
    {
        $this->_actionInternal(new OrdersSettingsForm(), ShopSetting::$orders);
    }     
    /**
     * Display navigation settings form.
     */
    public function actionNavigation()
    {
        $this->_actionInternal(new NavigationSettingsForm(), ShopSetting::$navigation);
    }     
    /**
     * Display marketing settings form.
     */
    public function actionMarketing()
    {
        $form = new MarketingSettingsForm();
        
        if (isset($_GET['service'])&&$_GET['service']==Feature::$addShopToFacebookPage){//successful service check
            $redirectUri = url('shop/settings/marketing?fbshop=installed');
            $script = <<<EOJS
url='http://www.facebook.com/dialog/pagetab?app_id=$form->fbPageAppId&redirect_uri=$redirectUri';
var win = window.open(url,'_self');
win.focus(); 
EOJS;
        }
        
        //fbshop is custom param to indicate facebook shop installed
        if (isset($_GET['fbshop']) && isset($_GET['tabs_added'])){//facebook returning the tab id after successful page tab added
            try {
                logInfo(__METHOD__.' data returned from facebook',$_GET['tabs_added']);
                $form->fbPageLink = $form->lookupFbPageLink($_GET['tabs_added']);
            } catch (CException $e) {
                $form->fbPageData = null;//unset fbPageData since fb page link fails
                user()->setFlash('Shop',[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Facebook page link error'),
                ]);
            }
        }
        
        $this->_actionInternal(
                $form, 
                ShopSetting::$marketing, 
                $form->isFbPageLinked?['fbPageLink'=>$form->fbPageLink,'fbPageData'=>$form->fbPageData]:null,
                isset($script)?$script:null);
    }     
    /**
     * Display brand settings form.
     */
    public function actionBrand()
    {
        $this->_actionInternal(new BrandSettingsForm(), ShopSetting::$brand);
    }
    /**
     * Display seo settings form.
     */
    public function actionSeo()
    {
        $this->_actionInternal(new SeoSettingsForm(), ShopSetting::$seo);
    }
    /**
     * Display chatbot settings form.
     */
    public function actionChatbot()
    {
        $this->_actionInternal(new ChatbotSettingsForm(), ShopSetting::$chatbot);
    }
    /**
     * Display notifications settings form.
     */
    public function actionNotifications()
    {
        $this->_actionInternal(new NotificationsSettingsForm(), ShopSetting::$notifications);
    }     
    /**
     * OVERRIDDEN METHOD from Trait
     * @see SettingsControllerTrait::getOwnerModel()
     * @return type
     * @throws CException
     */    
    protected function getOwnerModel()
    {
        if (!$this->hasParentShop()){
            $search = current(array_keys($_GET));//take the first key as search attribute: shop slug
            logTrace(__METHOD__.' $_GET, search key='.$search, $_GET);
            if (empty($search)){
                logTrace(__METHOD__.' no search key or session object, redirect to /shops');
                $this->redirect(url('shops'));
            }
                
            $shopModel = Shop::model()->merchant()->findByAttributes(['slug'=>$search]);
            if ($shopModel==null)
                throw new CException(Sii::t('sii','Shop not found'));
            if (!$this->hasSessionShop()){
                $this->setSessionShop($shopModel);
                $this->setParentShop($shopModel);
                logTrace(__METHOD__.' $_GET a', $_GET);
            }
        }
        else {
            $shopModel = $this->getParentShop();
        }
                
        if ($shopModel->settings===null){
            $shopModel->settings = new $this->modelType;
            $shopModel->settings->shop_id = $shopModel->id;
        }

        return $shopModel;
    }
    
    public function getSettingsMenu($model,$setting=null)
    {
        return [
            ['id'=>'view','title'=>Sii::t('sii','View Settings'),'subscript'=>Sii::t('sii','view'), 'url'=>url('shops/settings'),'linkOptions'=>['class'=>$setting==null?'active':'']],
            ['id'=>'cart','title'=>Sii::t('sii','Checkout Settings'),'subscript'=>Sii::t('sii','checkout'), 'url'=>url('shops/settings/checkout'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$checkout?'active':'']],
            ['id'=>'po','title'=>Sii::t('sii','Orders Settings'),'subscript'=>Sii::t('sii','orders'), 'url'=>url('shops/settings/orders'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$orders?'active':'']],
            ['id'=>'navigate','title'=>Sii::t('sii','Navigation Settings'),'subscript'=>Sii::t('sii','navigation'), 'url'=>url('shops/settings/navigation'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$navigation?'active':'']],
            ['id'=>'notify','title'=>Sii::t('sii','Notifications Settings'),'subscript'=>Sii::t('sii','notifications'), 'url'=>url('shops/settings/notifications'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$notifications?'active':'']],
            ['id'=>'marketing','title'=>Sii::t('sii','Marketing Settings'),'subscript'=>Sii::t('sii','marketing'), 'url'=>url('shops/settings/marketing'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$marketing?'active':'']],
            ['id'=>'brand','title'=>Sii::t('sii','Brand Settings'),'subscript'=>Sii::t('sii','brand'), 'url'=>url('shops/settings/brand'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$brand?'active':'']],
            ['id'=>'seo','title'=>Sii::t('sii','SEO Settings'),'subscript'=>Sii::t('sii','seo'), 'url'=>url('shops/settings/seo'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$seo?'active':'']],
            ['id'=>'chatbot','title'=>Sii::t('sii','Chatbot Settings'),'subscript'=>Sii::t('sii','chatbot'), 'url'=>url('shops/settings/chatbot'),'visible'=>$model->updatable(),'linkOptions'=>['class'=>$setting==ShopSetting::$chatbot?'active':'']],
        ];
    }
    
}