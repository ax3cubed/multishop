<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("plans.controllers.SubscriptionControllerTrait");
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends BillingBaseController 
{
    use SubscriptionControllerTrait;   
    /**
     * Index page
     */
    public function actionIndex()
    {
        $this->pageTitle = Sii::t('sii','Billing');
        $this->render('index',['dataProvider'=>new CArrayDataProvider(user()->onlineSubscriptions)]);
    }    
}
