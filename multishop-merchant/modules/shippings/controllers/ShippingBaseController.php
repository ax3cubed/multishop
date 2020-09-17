<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopParentController');
/**
 * Description of ShippingBaseController
 *
 * @author kwlok
 */
class ShippingBaseController extends ShopParentController 
{
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsController = false;
        $this->loadSessionParentShop();
    }     
}