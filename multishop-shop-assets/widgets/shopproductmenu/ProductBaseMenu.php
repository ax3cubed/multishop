<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.SWidget");
/**
 * Description of ProductBaseMenu
 *
 * @author kwlok
 */
abstract class ProductBaseMenu extends SWidget
{
    private $_menu;//menu instance
    public  $menuId;
    public  $menuName;
    public  $menuCssClass;
    /**
     * The path alias to access assets
     * @property string
     */
    public $pathAlias = 'shopwidgets.shopproductmenu.assets';
    /**
     * string the asset name of the widget
     */
    public $assetName = 'shopproductmenu';
    /**
     * Menu type
     */
    public $type;
    /**
     * Menu locale
     */
    public $locale;
    /**
     * ShopViewPage the page object
     */
    public $page;
    /**
     * string the active menu item
     */
    public $activeMenuItem;
    /**
     * True to use javascript "browse()", false to use direct url
     * @var boolean Default to true
     */
    public $useJavascript = true;
    /**
     * Get menu instance
     */
    public function getMenu()
    {
        if (!isset($this->_menu)){
            $this->_menu = new ShopBrowseMenu($this->type, $this->page->shopModel, $this->controller);
            $this->_menu->currentPage = $this->page;
            $this->_menu->useJavascript = $this->useJavascript;
            $this->_menu->getData($this->locale,$this->activeMenuItem);
        }
        return $this->_menu;
    }        
    /**
     * Function to publish and register assets on page 
     * @see SAssetManager::prepareShopWidgetsAssetBundle() 
     * @throws CException
     */
    public function publishAssets()
    {
        //Skip; Shop widgets assets files loading is triggered by changes in theme assets.
    }           
}
