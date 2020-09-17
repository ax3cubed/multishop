<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.UserLoginMenu");
/**
 * Description of MerchantLoginMenu
 *
 * @author kwlok
 */
class MerchantLoginMenu extends UserLoginMenu 
{
    /**
     * Menu constructor
     * @param type $user
     * @param array $config
     */
    public function __construct($user,$config=[]) 
    {
        parent::__construct($user, $config);
        
        $this->items[static::$messages] = $this->getMessageMenu();
        $this->items[static::$shops] = new UserMenuItem([
            'id'=> static::$shops,
            'label'=>Sii::t('sii','Manage Shops'),
            'icon'=>'<i class="material-icons md-24">store</i>',
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('shops'),
            'visible'=>$user->isAuthorizedActivated,
            'items'=>[],//auto populated when shop is known (user clicks to view shop)
            //@see MerchantControllerManager
        ]);
        $this->items[static::$orders] = new UserMenuItem([
            'id'=> static::$orders,
            'label'=>Sii::t('sii','Manage Orders'),
            'icon'=>'<i class="material-icons md-24">content_copy</i>',
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('purchase-orders'),
            'visible'=>$user->isAuthorizedActivated,
        ]);
        $this->items[static::$customers] = new UserMenuItem([
            'id'=> static::$customers,
            'label'=>Sii::t('sii','Manage Customers'),
            'icon'=>'<i class="fa fa-fw fa-database"></i>',
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('customers'),
            'visible'=>$user->isAuthorizedActivated,
        ]);  
        $this->items[static::$profile] = $this->getProfileMenu();
        //$this->items[static::$account] = $this->getAccountMenu();
        $this->items[static::$help] = new UserMenuItem([
            'id'=> static::$help,
            'label'=>Sii::t('sii','Help Center'),
            'icon'=>'<i class="fa fa-fw fa-support"></i>',
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('help'),
            'visible'=>$user->isAuthorized,
        ]);  
        $this->items[static::$logout] = $this->getLogoutMenu();
    }
    
    public function getAccountMenuItems() 
    {
        $items = [
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive(['accounts/management/index','accounts/management/email','accounts/management/password'])?'active':''], Sii::t('sii','My Account')), 'url'=>url('account'),'active'=>$this->isMenuActive(['accounts/management/index','accounts/management/email','accounts/management/password']),'visible'=>$this->user->isRegistered],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive(['billings/management/index','billings/settings/index','billings/subscriptions/index','billings/receipt/view','billings/payment/index','billings/payment/create','billings/payment/change','billings/subscription/view','billings/subscription/update'])?'active':''], Sii::t('sii','Billing')), 'url'=>url('billing'),'active'=>$this->isMenuActive(['billings/management/index','billings/settings/index','billings/subscriptions/index','billings/receipt/view','billings/payment/index','billings/payment/create','billings/payment/change','billings/subscription/view','billings/subscription/update']),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive(['notifications/subscription/index'])?'active':''], Sii::t('sii','Notifications')), 'url'=>url('notifications'),'active'=>$this->isMenuActive('notifications/subscription/index'),'visible'=>$this->user->isAuthorizedActivated],
        ];
        if (Yii::app()->controller->allowOAuth){
            $items = array_merge($items,[
                ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('accounts/management/networks')?'active':''], Sii::t('sii','Linked Accounts')), 'url'=>url('account/management/networks'),'active'=>$this->isMenuActive('accounts/management/networks'),'visible'=>$this->user->isRegistered],
            ]);
        }
        return $items;
    }

    public function getProfileMenuItems() 
    {
        return [
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('accounts/profile/index')?'active':''], Sii::t('sii','Profile')),'url'=>url('account/profile'),'active'=>$this->isMenuActive('accounts/profile/index'),'visible'=>$this->user->isRegistered],
            //todo Hide ‘Ask question’ and FAQ’ page for now. - Refer #279: [Product] Shop enquiry messages and FAQ management  is ready.
//            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('likes/management/index')?'active':''], Sii::t('sii','Likes')), 'url'=>url('likes'),'active'=>$this->isMenuActive('likes/management/index'),'visible'=>$this->user->isAuthorizedActivated],
//            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('comments/management/index')?'active':''], Sii::t('sii','Comments')), 'url'=>url('comments'),'active'=>$this->isMenuActive('comments/management/index'),'visible'=>$this->user->isAuthorizedActivated],
            //The question below are for community questions
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('questions/customer/index')?'active':''], Sii::t('sii','Questions')), 'url'=>url('questions'),'active'=>$this->isMenuActive('questions/customer/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('tutorials/management/index')?'active':''], Sii::t('sii','Tutorials')), 'url'=>url('tutorials/management/index'),'active'=>$this->isMenuActive('tutorials/management/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('payments/merchant/index')?'active':''], Sii::t('sii','Payments')),'url'=>url('account/payments'),'active'=>$this->isMenuActive('payments/merchant/index'),'visible'=>$this->user->isRegistered],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('media/management/index')?'active':''], Sii::t('sii','Media')),'url'=>url('media'),'active'=>$this->isMenuActive('media/management/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('tickets/management/index')?'active':''], Sii::t('sii','Tickets')), 'url'=>url('tickets/management/index'),'active'=>$this->isMenuActive('tickets/management/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('activities/merchant/index')?'active':''], Sii::t('sii','Activities')),'url'=>url('activities'),'active'=>$this->isMenuActive('activities/merchant/index'),'visible'=>$this->user->isAuthorizedActivated],
        ];        
    }
    /**
     * Construct and return shop menu
     * @return array
     */
    public function getShopMenu($model)
    {
        return [
            ['label'=>SButtonColumn::getButtonToolTip('view',Sii::t('sii', 'Shop Information')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Overview')), 'url'=>$model->viewUrl,'active'=>$this->isMenuActive(['shops/management/view','shops/management/update'])],
            ['label'=>SButtonColumn::getButtonToolTip('dashboard',Sii::t('sii', 'Shop Dashboard')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Dashboard')), 'url'=>url('dashboard'),'active'=>$this->isMenuActive(['analytics/management']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('product',Sii::t('sii', 'Products Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Products')), 'url'=>url('products'),'active'=>$this->isMenuActive(['products/management','products/category','products/attribute']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('inventory',Sii::t('sii', 'Inventory Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Inventories')), 'url'=>url('inventories'),'active'=>$this->isMenuActive(['inventories/management','products/inventory']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('shipping',Sii::t('sii', 'Shippings Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Shippings')), 'url'=>url('shippings'),'active'=>$this->isMenuActive(['shippings/management','shippings/zone']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('payment',Sii::t('sii', 'Payment Methods Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Payments')), 'url'=>url('paymentMethods'),'active'=>$this->isMenuActive(['payments/management']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('tax',Sii::t('sii', 'Taxes Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Taxes')), 'url'=>url('taxes'),'active'=>$this->isMenuActive(['taxes/management']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('news',Sii::t('sii', 'News Blog')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'News')), 'url'=>url('news'),'active'=>$this->isMenuActive(['news/management']),'visible' => $model->updatable()],
            //Refer to #279: [Product] Shop enquiry messages and FAQ management  is ready.
            //This is open for product level questions management. 
            //todo Make this feature optional to shop and only shop subscribe to this feature can access
            ['label'=>SButtonColumn::getButtonToolTip('question',Sii::t('sii', 'Questions Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Questions')), 'url'=>url('questions/management/index'),'active'=>$this->isMenuActive(['questions/management']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('campaign',Sii::t('sii', 'Campaigns Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Campaigns')), 'url'=>url('campaigns'),'active'=>$this->isMenuActive(['campaigns/management','campaigns/bga','campaigns/sale']),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('design',Sii::t('sii', 'Themes Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Themes')), 'url'=>url('shop/themes'),'active'=>$this->isMenuActive('shops/design/index'),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('pages',Sii::t('sii', 'Pages Management')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Pages')), 'url'=>url('pages'),'active'=>$this->isMenuActive('pages/management'),'visible' => $model->updatable()],
            ['label'=>SButtonColumn::getButtonToolTip('settings',Sii::t('sii', 'Shop Settings')).CHtml::tag('span',['class'=>'mobile-display-only'],Sii::t('sii', 'Settings')), 'url'=>url('shop/settings'),'active'=>$this->isMenuActive(['shops/settings']),'visible' => $model->updatable()],
        ];
    }   
}
