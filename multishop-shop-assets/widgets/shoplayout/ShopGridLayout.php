<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.themes.widgets.themegridlayout.ThemeOffCanvasGridLayout");
Yii::import("shopwidgets.shoplayout.*");
/**
 * Description of ShopGridLayout
 *
 * @author kwlok
 */
class ShopGridLayout extends ThemeOffCanvasGridLayout
{    
    use ShopWidgets;    
    /**
     * Init widget
     */ 
    public function init()
    {
        $this->id = 'shop-layout '.$this->themeName.' '.$this->themeStyle;
        parent::init();
        /**
         * Note: Shop widgets assets "shopgridlayout.css/js" files loading is triggered by changes in theme assets.
         * @see SAssetManager::prepareShopWidgetsAssetBundle() 
         */
    }    
    /**
     * @inheritdoc
     */
    public function getThemeBasepath()
    {
        return param('SHOP_THEME_BASEPATH');
    }    
    /**
     * @inheritdoc
     */
    public function getThemeOwnerModel()
    {
        return $this->page->shopModel;
    }       

}
