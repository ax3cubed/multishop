<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("wcm.controllers.WcmLayoutTrait");
/**
 * Description of MerchantControllerManager
 * Manage controller stuff, like layouts, views
 * 
 * @author kwlok
 */
class MerchantControllerManager extends SControllerManager
{
    use WcmLayoutTrait;
    
    protected $shop;
    /**
     * Init
     */
    public function init()
    {
        parent::init();
        //layout before merchant logins
        $this->loadWcmLayout(Yii::app()->controller);
        $this->htmlBodyCssClass .= ' wcm merchant';//This is requried for login form to have the css effect
        //layout after merchant logins
        $this->authenticatedLayout = 'merchant.views.layouts.authenticated';
        $this->authenticatedHeaderView = 'merchant.views.layouts._authenticated_header';
        $this->authenticatedFooterView = 'merchant.views.layouts._authenticated_footer';
        /**
         * Google analytics is loaded only when it is enabled
         * @see SWebApp
         * @todo Does this also track authenticated layout? Previous implementation does not track 'login user' analytics
         */
        $this->htmlBodyBegin = Yii::app()->googleAnalytics->renderGTag();
        /**
         * Drift is loaded only when it is enabled; Default to false if not set in config.json
         * @see SWebApp
         * @see config.json
         */
        Yii::app()->drift->renderScript();
    }    
    
    public function getOffCanvasMenu($controller)
    {
        $siteLogo = $this->getSiteLogo(true);
        $menu = [];
        $menu[] = [
            'id'=>'offcanvas_login_menu',
            'heading'=>$siteLogo,
            'content'=>$controller->widget('common.widgets.susermenu.SUserMenu',[
                        'type'=>SUserMenu::LOGIN,
                        'user'=>user(),
                        'mergeWith'=>[SUserMenu::LANG],
                    ],true),
        ];
        
        $shop = $this->findActiveShop($controller);
        if ($shop!=null && $shop instanceof Shop){
            $menu[] = [
                'id'=>'offcanvas_shopmgmt_menu',
                'heading'=>$siteLogo.CHtml::tag('div',['class'=>'shop-name'],$shop->parseName(user()->getLocale())),
                'content'=>$controller->widget('ShopMenu',[
                        'user'=>user(),
                        'shop'=>$shop,
                        'offCanvas'=>true,
                    ],true),
            ];
        }
        return $menu;
    }
    
    public function getCanvasMenu($controller)
    {
        $controller->widget('common.widgets.susermenu.SUserMenu',[
            'type'=>SUserMenu::WELCOME,
            'user'=>user(),
            'cssClass'=>'nav-menu',
            'offCanvas'=>false,
            'mergeWith'=>[SUserMenu::LANG],
        ]);
        
        $shop = $this->findActiveShop($controller);
        if ($shop!=null && $shop instanceof Shop){
            $shopMenu = new ShopMenuContent(user(),[
                'shop'=>$shop,
                'offCanvas'=>false,
            ]);
            echo $shopMenu->mobileButton;
        }
    }
    
    protected function findActiveShop($controller)
    {
        if (SActiveSession::get(SActiveSession::SHOP_ACTIVE)!=null){
            return $controller->loadModel(SActiveSession::get(SActiveSession::SHOP_ACTIVE), 'Shop', false);
        }
    }
}
