<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.SUserMenu");
Yii::import("common.widgets.susermenu.components.*");
Yii::import("common.modules.shops.components.ShopViewPage");
Yii::import("common.modules.shops.components.ShopNavigation");
Yii::import("shopwidgets.shopmenu.ShopMobileMenuContent");
/**
 * Description of ShopOffCanvasMenu
 * This menu is for off canvas under mobile view
 * 
 * @author kwlok
 */
class ShopMobileMenu extends SUserMenu
{
    /**
     * The menu type; Fixed value
     * @var string 
     */
    public $type = 'shop';
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
        
        $this->render('common.widgets.susermenu.views.index');
    }  
    /**
     * OVERRIDE METHOD
     * @inheritdoc
     */
    public function getMenu($type)
    {
        $menu = (new ShopMobileMenuContent($this->user, $this->page))->menu;
        foreach ($this->mergeWith as $other) {
            if ($type!=$other)//not merging itself
                $menu = array_merge($menu,$this->{$other.'Menu'});
        }
        return $menu;
    }
    
}
