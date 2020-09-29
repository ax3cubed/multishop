<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * @author kwlok
 */
$this->widget('shopthemes.neat.NeatLayout',[
    /**
     * IMPORTANT NOTE: This step inits page and load all settings
     */
    'theme'=>$theme,
    'page'=>$page,
    'locale'=>user()->getLocale(),
    /**
     * Offcanvas menu
     */
    'shopMenu'=>[
        'content' => $this->widget('shopwidgets.shopmenu.ShopMobileMenu',[
                        'user'=>user(),
                        'page'=>$page,
                        'mergeWith'=>[SUserMenu::LANG],
                        'topSection'=>$this->widget('common.widgets.ssearch.SSearch',[
                            'placeholder'=>Sii::t('sii','Search'),
                            'url'=>$page->getUrl(ShopPage::SEARCH),
                        ],true),
                        'offSite'=>ShopNavigation::isOffSite(),
                    ],true),
    ],
    'loginMenu'=> user()->isAuthenticated 
        ?[
            'content' => $this->widget('common.widgets.susermenu.SUserMenu',['user'=>user(),'type'=>SUserMenu::LOGIN],true)
         ]
        :[], 
    'cartMenu'=> [
        'content' => $this->renderPartial($this->getThemeView('_cart_quickview'),[
                        'model'=>$page->shopModel,
                        'queryParams'=>$page->getExtraQueryParams(),
                    ],true),
    ],
    /**
     * Common widgets are loaded inside layout.json and ShopWidgets
     */
]);

$this->loadFavicon($page->shopModel);

$this->includeCustomCss($page);

if (isset($modalView))
    $this->smodalWidget('shop_modal',$modalView);