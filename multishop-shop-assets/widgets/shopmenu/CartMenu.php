<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.UserMenu");
Yii::import("common.widgets.susermenu.components.UserMenuItem");
/**
 * Description of CartMenu
 *
 * @author kwlok
 */
class CartMenu extends UserMenu 
{
    protected $counter=0;
    protected $button;
    protected $onclick = 'openoffcanvascartmenu();';
    /**
     * Constructor
     * @param type $user
     */
    public function __construct($user,$shop,$url=null,$onclick=null) 
    {
        if (userOnScope('shop')){
            $this->counter = $user->getCartCount($shop->id); 
        }
        if (isset($onclick))
            $this->onclick = $onclick;
        
        $this->items[static::$cart] = new UserMenuItem([
            'id'=> static::$cart,
            'label'=>$this->getCounterHtml(),
            'icon'=>'<i class="fa fa-shopping-cart"></i>',
            'url'=>isset($url)?$url:'javascript:void(0);',
            'onclick'=>isset($url)?null:$this->onclick,
            'cssClass'=>$user->isGuest?'':'quickaccess cart',
        ]);
        
        $this->button = CHtml::openTag('div',['class'=>'mobile-button mobile-cart']);
        $this->button .= CHtml::link('<i class="fa fa-shopping-cart"></i>'.$this->getCounterHtml(),isset($url)?$url:'javascript:void(0);',['onclick'=>isset($url)?null:$this->onclick]);
        $this->button .= CHtml::closeTag('div');
        
    }        
    
    public function getCounterHtml()
    {
        return ($this->counter==0?'':'<span class="cart counter">'.$this->counter.'</span>');
    }
    
    public function getMobileButton()
    {
        return $this->button;
    }
}