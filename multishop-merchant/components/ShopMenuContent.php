<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.*");
/**
 * Description of ShopMenuContent
 * 
 * @author kwlok
 */
class ShopMenuContent extends UserMenu 
{
    public $shop;
    /**
     * Constructor
     */
    public function __construct($user,$config=[]) 
    {
        $this->user = $user;
        $this->loadConfig($config);
        if ($this->offCanvas){ //only required for off canvas
            $merchantMenu = new MerchantLoginMenu($user);
            $this->items['shop'] = new UserMenuItem([
                'id'=> 'shop',
                'label'=>null,
                'iconDisplay'=>false,
                'url'=>'javascript:void(0);',
                'cssClass'=>'shop-mgmt',
                'items'=>$merchantMenu->getShopMenu($this->shop),
            ]);
        }
    }  
    
    public function getMobileButton()
    {
        $button = CHtml::openTag('div',['class'=>'mobile-button mobile-shopmgmt']);
        $button .= CHtml::link('<i class="material-icons md-24">store</i>','javascript:void(0);',['onclick'=>'openoffcanvasshopmgmtmenu();']);
        //$button .= CHtml::link(CHtml::tag('i',['class'=>'fa avatar'],$this->shop->getImageThumbnail(Image::VERSION_XSMALL)),'javascript:void(0);',['onclick'=>'openoffcanvasshopmgmtmenu();']);
        $button .= CHtml::closeTag('div');
        return $button;        
    }
}