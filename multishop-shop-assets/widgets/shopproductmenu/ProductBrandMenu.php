<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("shopwidgets.shopproductmenu.ProductBaseMenu");
/**
 * Description of ProductBrandMenu
 *
 * @author kwlok
 */
class ProductBrandMenu extends ProductBaseMenu
{
    /**
     * Menu type
     */
    public $type = ShopBrowseMenu::BRAND;
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        $this->menuId = $this->type.'_menu';
        $this->menuCssClass = 'brand-menu';
        $this->menuName = Sii::t('sii','Brands');
        if ($this->menu->hasData){
            $this->render('index'); 
        }        
    } 
}
