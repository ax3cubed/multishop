<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.*");
/**
 * Description of ShopMenu
 * 
 * @author kwlok
 */
class ShopMenu extends SUserMenu
{
    /**
     * The shop that user is visiting; 
     * @var Shop 
     */
    public $shop;
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if (!isset($this->user))
            throw new CException(__CLASS__." User cannot be null");
        
        if (!isset($this->shop))
            throw new CException(__CLASS__." Shop cannot be null");
        
        $this->renderMenu(new ShopMenuContent($this->user,['shop'=>$this->shop,'iconDisplay'=>$this->offCanvas,'offCanvas'=>$this->offCanvas]));
    }  
}