<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of BillingControllerTrait
 *
 * @author kwlok
 */
trait BillingControllerTrait 
{
    public function getBillingMenu($active=null)
    {
        $menu = [
            ['id'=>'view','title'=>Sii::t('sii','Billing Overview'),'subscript'=>Sii::t('sii','billing'), 'url'=>url('billing')],
            ['id'=>'credit-card','title'=>Sii::t('sii','Payment Cards'),'subscript'=>Sii::t('sii','payment'), 'url'=>url('billing/payment')],
            ['id'=>'settings','title'=>Sii::t('sii','Billing Settings'),'subscript'=>Sii::t('sii','settings'), 'url'=>url('billing/settings')],
            ['id'=>'history','title'=>Sii::t('sii','Subscription History'),'subscript'=>Sii::t('sii','history'), 'url'=>url('subscriptions')],
        ];
        foreach ($menu as $key => $item) {
            if ($active==$item['id'])
                $menu[$key]['linkOptions'] = ['class'=>'active'];
        }
        return $menu;
    } 
}
