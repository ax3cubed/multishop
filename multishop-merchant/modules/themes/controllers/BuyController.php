<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('themes.controllers.ThemeViewControllerTrait');
Yii::import('common.modules.shops.models.ShopTheme');
/**
 * Description of BuyController
 *
 * @author kwlok
 */
class BuyController extends ThemeBaseController
{
    use ThemeViewControllerTrait;
    /**
     * Below property are used for theme purchases
     */
    protected $shop;
    /**
     * Parse uri
     * Expect format:
     * https://<merchant_domain>/themes/buy/<theme>?shop=<shop_id>
     */
    protected function parseUri()
    {
        parent::parseUri();
        $this->shop = isset($this->uri['query']['shop']) ? $this->findShop($this->uri['query']['shop']) : null;
    }    
    /**
     * Index action
     * @see ThemeBaseController::parseUri() for query parsing
     */
    public function actionIndex()
    {
        if (isset($_POST['ThemePaymentForm'])){
            //Process payment!
            $this->run('confirm');
            Yii::app()->end();
        }
        
        if (isset($this->theme) && isset($this->shop)){
            $this->pageTitle = Sii::t('sii','{theme} | Theme Store',['{theme}'=>$this->theme->displayLanguageValue('name',user()->getLocale())]);
            $this->render('view',['theme'=>$this->theme,'shop'=>$this->shop]);
        }
        else
            throwError404(Sii::t('sii','Theme not found'));
    }

    /**
     * Confirm purchase action
     */
    public function actionConfirm()
    {
        $form = new ThemePaymentForm();
        
        if (isset($_POST['ThemePaymentForm']['theme']) && isset($_POST['ThemePaymentForm']['shop_id'])){
            try {
                $form->attributes = $_POST['ThemePaymentForm'];//mainly to capture payment token
                if (!$form->validate()){
                    logError(__METHOD__.' validaiton error',$form->errors);
                    throw new CException(Sii::t('sii','Please select payment method.'));
                }

                $shopTheme = $this->module->serviceManager->buy(user()->getId(),$form);
                unset($_POST);
                user()->setFlash('ShopThemeForm',[
                    'message'=>Sii::t('sii','You have purchased theme {name} successfully.',['{name}'=>$shopTheme->model->displayLanguageValue('name',user()->getLocale())]),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Theme Purchase'),
                ]);
                $this->redirect($shopTheme->viewUrl);//show the default theme style 
                Yii::app()->end();
                
            } catch (CException $ex) {
                logError(__METHOD__.' error',$ex->getTraceAsString());
                user()->setFlash('Theme',[
                    'message'=>$ex->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Theme Purchase Error'),
                ]);
                //redirect back to buy theme url with proper url path
                $this->redirect($this->getBuyUrl($_POST['ThemePaymentForm']['theme'],$_POST['ThemePaymentForm']['shop_id']));
                Yii::app()->end();
            }
        }
        throwError403(Sii::t('sii','Unauthorized access.'));
    }
    
    public function getPaymentForm(Theme $theme, Shop $shop)
    {
        $form = new ThemePaymentForm();
        $form->amount = $theme->price;
        $form->currency = $theme->currency;
        $form->theme = $theme->theme;
        $form->shop_id = $shop->id;
        return $form;
    }
    
    protected function getBuyUrl($theme,$shop)
    {
        return url('themes/buy/'.$theme.'?shop='.$shop);
        
    }
    
    protected function findShop($shopId)
    {
        return Shop::model()->mine()->find('id='.$shopId);
    }
}
