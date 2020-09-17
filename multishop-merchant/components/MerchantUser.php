<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of MerchantUser
 *
 * @author kwlok
 */
class MerchantUser extends WebUser 
{    
    /**
     * Init
     */
    public function init()
    {
        parent::init();
        $this->currentRole = Role::MERCHANT;
    }
        
    public function afterLogin($fromCookie)
    {
        parent::afterLogin($fromCookie);
    }  
    /**
     * @return boolean if merchant has any shops 
     */
    protected function getHasShops()
    {
        return Shop::model()->mine()->exists();
    }    
    /**
     * Construct and return shop menu 
     * @return array
     */
    public function getShopMenu($shop)
    {
        return $this->getMenuInternal('getShopMenu',$shop);
    }       
    /**
     * Construct and return profile menu according to role
     * @return array
     */
    public function getProfileMenu()
    {
        return $this->getMenuInternal('getProfileMenuItems');
    }    
    /**
     * Construct and return account menu according to role
     * @return array
     */
    public function getAccountMenu()
    {
        return $this->getMenuInternal('getAccountMenuItems');
    }       
    
    protected function getMenuInternal($menuMethod,$param=null)
    {
        $loginMenu = new MerchantLoginMenu($this);
        return $loginMenu->{$menuMethod}($param);
    }    
}