<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('community.components.CommunityUrlRules');
/**
 * Description of MerchantUrlManager
 *
 * @author kwlok
 */
class MerchantUrlManager extends SUrlManager 
{
    /**
     * @property array the merchant rules to be loaded
     */
    public $merchantRules = [
        'account/payments'=>'payments/merchant/index',
        'account/payments/*'=>'payments/merchant/index/*',
        'payment/view/*'=>'payments/merchant/view',
    ];
    /**
     * @property array the wcm rules to be loaded into SUrlManager
     */
    public $wcmRules = [
        'index'=>'wcm/content/index',//default landing page, @see WcmMenu 
        'contact'=>'wcm/site/contact',
        'features'=>'wcm/content/features',
        'features/*'=>'wcm/content/features/*',
        'pricing'=>'wcm/content/pricing',
        'about'=>'wcm/site/about',//for static web content page 
        'terms'=>'wcm/site/terms',//for static web content page 
        'privacy'=>'wcm/site/privacy',//for static web content page 
        'careers'=>'wcm/site/careers',//for static web content page
    ];    
    /**
     * Initializes the application component.
     */
    public function init()
    {
        //load merchant rules first to take precedence over default rules
        $this->defaultRules = array_merge(
                                $this->merchantRules,
                                $this->defaultRules,
                                $this->wcmRules,
                                CommunityUrlRules::$rules
                            );
        parent::init();
        $this->merchantDomain = $this->hostDomain;
    }

}
