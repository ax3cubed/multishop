<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.shops.components.ShopViewPage");
Yii::import("common.widgets.susermenu.SUserMenu");
Yii::import("common.widgets.susermenu.components.*");
Yii::import("shopwidgets.shopmenu.ShopNavMenuContent");
Yii::import("shopwidgets.shopmenu.ShopMobileMenuContent");
Yii::import("shopwidgets.shopmenu.CartMenu");
/**
 * Description of ShopNavMenu
 * Shop (with sub domain and allows customer register/login) navigation menu - (desktop menu, plus mobile menu openers)
 *
 * @author kwlok
 */
class ShopNavMenu extends SUserMenu
{
    /**
     * The menu type; Fixed value
     * @var string 
     */
    public $type = 'shopnav';
    /**
     * The shop cart button open script; 
     * @see CartMenu::$onclick for default value
     */
    public $cartScript;
    /**
     * The shop cart url (optional for Nav menu); Default is to use javascript
     */
    public $cartUrl;    
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if (!isset($this->user))
            throw new CException(__CLASS__." User cannot be null");
        
        if (!isset($this->page))
            throw new CException(__CLASS__." Page cannot be null");

        if (userOnScope('shop')){
            $this->user->setShop($this->page->shopModel->id);
        }

        if ($this->page->edit){
            $this->user = new WebUser();//under edit mode, create a fake user
        }

        $navMenu = new ShopNavMenuContent($this->user,$this->page,$this->offSite,$this->cartUrl,$this->cartScript);
        $this->render('nav',[
            'navMenu'=> $navMenu->menu,
            'shopMobileButton'=> (new ShopMobileMenuContent($this->user, $this->page,false))->mobileButton,
            'cartMobileButton'=> $navMenu->cartMenu->mobileButton,
            'welcomeMobileButton'=> isset($navMenu->welcomeMenu) && !$this->user->isGuest ? $navMenu->welcomeMenu->mobileButton : null,
        ]);
    }  
}