<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.*");
Yii::import("common.modules.shops.components.ShopViewPage");
Yii::import("common.modules.shops.components.ShopNavigation");
Yii::import("shopwidgets.shopmenu.ShopNavMenuContent");
/**
 * Description of ShopNavMenuContent
 * This menu is for off canvas under mobile view
 * 
 * @author kwlok
 */
class ShopMobileMenuContent extends ShopNavMenuContent 
{
    protected $nav;
    /**
     * Constructor
     * @param ShopViewPage $page
     */
    public function __construct($user, ShopViewPage $page,$loadData=true) 
    {
        $this->user = $user;
        if ($loadData){
            $this->nav = new ShopNavigation('navmenu', $page->shopModel, null);//controller set to null
            $this->nav->page = $page;
            $config = ['iconDisplay'=>false];
            $config['signinScript'] = $this->getSigninScript($page);
            $config['signupScript'] = $this->getSignupScript($page);
            $this->siteMenu = new SiteMenu($user,false,$config);
        }
        
    }     
    /**
     * OVERRIDE METHOD
     * @return type
     */
    public function getMenu()
    {
        return array_merge($this->nav->data,$this->siteMenu->menu);
    }
    
    public function getMobileButton()
    {
        $button = CHtml::openTag('div',['class'=>'mobile-button mobile-shop']);
        $button .= CHtml::link('<i class="fa fa-navicon"></i>','javascript:void(0);',['onclick'=>'openoffcanvasshopmenu();']);
        $button .= CHtml::closeTag('div');
        return $button;        
    }
}