<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.*");
Yii::import("common.modules.shops.components.ShopViewPage");
/**
 * Description of ShopNavMenuContent
 * Shop (with sub domain and allows customer register/login) navigation menu - (desktop menu, plus mobile menu openers)
 * 
 * @author kwlok
 */
class ShopNavMenuContent extends UserMenu 
{
    protected $submenus = [];
    public $cartMenu;
    public $langMenu;
    public $welcomeMenu;
    public $siteMenu;
    /**
     * Constructor
     */
    public function __construct($user, ShopViewPage $page, $offSite=false,$cartUrl=null,$cartScript=null) 
    {
        $this->user = $user;
        $this->cartMenu = new CartMenu($user, $page->shopModel,$cartUrl,$cartScript);
        $this->langMenu = new LangMenu($user);
        $this->submenus[static::$cart] = $this->cartMenu->menu;
        $this->submenus[static::$lang] = $this->langMenu->menu;
        if ($user->isGuest||$offSite) {
            
            $config = ['iconDisplay'=>false];
            if (userOnScope('shop')){//when shop come with subdomain and supports customer account login/registration
                $config['signinScript'] = $this->getSigninScript($page, $offSite);
                $config['signupScript'] = $this->getSignupScript($page, $offSite);
            }
            if ($page->edit){//edit mode have to change signin / signup script
                $config['signinScript'] = '';
                $config['signupScript'] = '';
            }
            $this->siteMenu = new SiteMenu($user,$offSite,$config);
            $this->submenus[static::$site] = $this->siteMenu->menu;
        }
        elseif (!$offSite) {
            $this->welcomeMenu = new WelcomeMenu($user);
            $this->submenus[static::$welcome] = $this->welcomeMenu->menu;
        }        
    }  
    /**
     * OVERRIDE METHOD
     * @return array
     */
    public function getMenu()
    {
        if ($this->user->isGuest)
            $sequence = [static::$cart,static::$lang,static::$site];
        else {
            if (app()->controller->action->id=='checkout')//for checkout page no need to show cart menu anymore
                $sequence = [static::$lang,static::$welcome];
            else
                $sequence = [static::$cart,static::$lang,static::$welcome];
        }        
        return $this->sortMenu($sequence);
    }  
    
    public function sortMenu($sequence=[])
    {
        $result = [];
        if (!empty($sequence)){
            foreach ($sequence as $menu)
                if ($menu!=null)
                    $result = array_merge($result,$this->submenus[$menu]);
        }
        else {
            foreach($this->submenus as $id => $menu)
                $result = array_merge($result,$menu);
        }
        return $result;
    }    
    /**
     * Dnaymic signin script
     * @param type $offSite True when page is loaded outside Multishop, such as facebook
     * @return type
     */
    public function getSigninScript(ShopViewPage $page,$offSite=false)
    {
        $url = $page->appendExtraQueryParams($page->shopModel->getUrl(request()->isSecureConnection).'/login');
        return $offSite ? 'newwindowpage("'.$url.'")' : 'window.location.href = "'.$url.'"' ;
    }
    /**
     * Dnaymic signup script
     * @param type $offSite True when page is loaded outside Multishop, such as facebook
     * @return type
     */
    public function getSignupScript(ShopViewPage $page,$offSite=false)
    {
        $url = $page->appendExtraQueryParams($page->shopModel->getUrl(request()->isSecureConnection).'/register');
        return $offSite ? 'newwindowpage("'.$url.'")' : 'window.location.href = "'.$url.'"' ;
    }        
    
}
