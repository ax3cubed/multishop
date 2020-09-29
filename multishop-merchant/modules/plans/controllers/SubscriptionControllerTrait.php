<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.components.actions.api.ApiIndexAction');
Yii::import('common.components.actions.api.ApiDataProvider');
Yii::import('common.modules.plans.models.Subscription');
/**
 * Description of SubscriptionControllerTrait
 *
 * @author kwlok
 */
trait SubscriptionControllerTrait 
{
    protected $showFeatureDetails = false;//if true, it will show full details and not summary
    private $_packages;
    /**
     * Get all published packages
     */
    public function getPublishedPackages($removeFreeTrial=false)
    {
        if (!isset($this->_packages)){
            if (Yii::app()->commonCache->get(SCache::PUBLISHED_PACKAGES)==null){
                $action = new ApiIndexAction($this,__METHOD__);
                $action->user = Account::SYSTEM;
                $action->model = 'Package';
                $action->apiRoute = '/packages/published';
                $action->retryAccessToken = true;
                $action->afterIndex = 'preparePublishedPackages';
                $this->runAction($action);        
                logInfo(__METHOD__.' load published packages from "'.$action->apiEndpoint.'"');
            }
            else 
                logInfo(__METHOD__.' load published packages from cache');            
            
            $this->_packages = Yii::app()->commonCache->get(SCache::PUBLISHED_PACKAGES);    
        }
        
        if ($removeFreeTrial){
            $dataProvider = $this->_packages;
            foreach ($this->_packages->rawData as $pkg) {
                if ($pkg->isTrial){
                    unset($dataProvider->rawData[0]);
                }                
            }
            return $dataProvider;
        }   
        else
            return $this->_packages;
    }  
    /**
     * Prepare data provider (a call back from ApiIndexAction)
     * Exclude non-business ready packages
     * 
     * @see common.components.actions.api.ApiIndexAction
     * @param type $items
     * @param type $pagination
     * @param type $links
     */
    public function preparePublishedPackages($items, $pagination, $links)
    {
        foreach ($items as $index => $pkg) {
            if (!$pkg->businessReady){
                logTrace(__METHOD__.' Exclude non-business-ready package',$pkg->name);
                unset($items[$index]);//exclude non-business ready package
            }
        }
            
        Yii::app()->commonCache->set(SCache::PUBLISHED_PACKAGES,new ApiDataProvider($items,[
            'keyField'=>false,
            'sort'=>false,
            'pagination'=>$pagination,                
            'totalCount'=>$pagination->getItemCount(),
        ]),3600*24*30);//30 days expiry
    }     
    /**
     * Calculate the main rate of each plan
     * @param type $plans
     * @return type
     */
    public function getMainRate($plans)
    {
        $rate = new stdClass();
        $parsePrice = function ($price) use($rate) {
            $price = number_format((float)$price,2);
            $_p = explode('.', $price);
            $rate->dollar = $_p[0];
            $rate->cents = 0;
            if (isset($_p[1]))
                $rate->cents = $_p[1];
            return $rate;
        };
        
        foreach ($plans as $plan) {
            $rate->currency = $plan['currency'];
            if ($plan['recurring']==Plan::YEARLY){//If yearly rate exists, it will take precedence
                //Here each plan data is strucutred that yearly plan is loaded at later sequence hence will override monthly plan
                $rate = $parsePrice($plan['price']/12);
                $rate->details = Sii::t('sii','per month billed annually');
            }
            elseif ($plan['recurring']==Plan::MONTHLY){//If not, use montly rate 
                $rate = $parsePrice($plan['price']);
                $rate->details = Sii::t('sii','per month');
            }
            elseif ($plan['type']==Plan::FIXED && $plan['price']==0){//for free plan if any
                $rate = $parsePrice($plan['price']);
                $rate->details = Sii::t('sii','for small business or unlimited evaluation');
            }
            elseif ($plan['type']==Plan::CONTRACT){
                $rate = $parsePrice($plan['price']);
                $rate->details = Sii::t('sii','for anything you want to add or customize');
            }
            elseif ($plan['type']==Plan::TRIAL){
                $rate = $parsePrice($plan['price']);
                $rate->details = Sii::t('sii','for free trial');
            }
        }
        return $rate;
    }    
    /**
     * Calculate the monthly price of each plan
     * @param type $plans Contains all the recurring plans (monthly, yearly)
     * @return type
     */
    public function getMonthlyRate($plans)
    {
        $rate = new stdClass();
        $rate->price = 0;
        //logTrace(__METHOD__.' plan data',$plans);
        $yearlyPlan = false;
        foreach ($plans as $plan) {
            if ($plan['recurring']==Plan::YEARLY){
                $yearlyPlan = true;//There exists a yearly plan
            }
            if ($plan['recurring']==Plan::MONTHLY){
                $rate->currency = $plan['currency'];
                $rate->price = number_format((float)$plan['price'],2);
            }
        }
        //Only return monthly rate if there exists a yearly plan
        //Else, the monthly rate already covered in main rate
        return ($yearlyPlan && $rate->price>0)?$rate:null;
    }
    /**
     * Render button
     * Display proper button caption based on user subscription and package price
     */
    public function renderPackageButton($package,$price,$shop=null)
    {
        //if shop is null or invalid, subscription object will be null as well
        $subscription = user()->getCurrentSubscription($shop);
        
        //prepare button default data
        $button = [
            'enable'=> true,
            'caption'=>  Sii::t('sii','Subscribe'),
            'actionUrl' => user()->isGuest ? Yii::app()->urlManager->createHostUrl('signup') : url('plans/subscription/checkout'),
            'freeTrial'=>false,
        ];
        
        if (!user()->hasTrialBefore && user()->hasNoShopBefore){
            $button['caption'] = Sii::t('sii','Start Free Trial');
            $button['freeTrial'] = true;
            $button['freeTrialId'] = Package::FREE_TRIAL;
        }
        elseif ($subscription instanceof Subscription){//existing subscription
            if ($subscription->package_id < $package->id)
                $button['caption'] = Sii::t('sii','Upgrade Plan');
            elseif ($subscription->package_id == $package->id && $subscription->hasExpired){
                $button['caption'] = Sii::t('sii','Renew Plan');
            }
            else {
                //downgrade is currently not supported
                //as need to consider complex scenario when user use more services in current plan
                //but if were to downgrade, need to cut off some services - have to handle manual and case by case basis
                //$button['caption'] = Sii::t('sii','Downgrade Plan');
                //$button['actionUrl'] = url('contact');
                $button['enable'] = false;
            }
        }
        elseif ($price==0 && $package->type!=Plan::CONTRACT){
            $button['caption'] = Sii::t('sii','Create Shop Now');
            if (user()->hasFreePlanBefore){//free shop only can subscribe one time
                $button['enable'] = false;
            }
        }
        elseif ($package->type==Plan::CONTRACT){
            $button['caption'] = Sii::t('sii','Contact us');
            $button['actionUrl'] = url('contact');
        }
        
        //render button
        if ($subscription instanceof Subscription && !$subscription->hasExpired && $subscription->package_id==$package->id){
            echo CHtml::tag('h3',[],Sii::t('sii','Your current plan'));
        }
        elseif ($button['enable'])
            $this->renderPartial('plans.views.subscription._button',['subscription'=>$subscription,'package'=>$package,'button'=>$button]);
    }    
    /**
     * Render feature summary
     */
    public function renderFeatureSummary($package)
    {
        if ($this->showFeatureDetails)
            $this->renderPartial('plans.views.subscription._feature_details',['package'=>$package]);
        else
            $this->renderFeatureSummaryFromFile($package->id);
    }
    /**
     * Render feature summary
     */
    public function renderFeatureSummaryFromFile($packageId)
    {
        echo $this->module->runControllerMethod('wcm/content','renderMarkdown','package_'.$packageId,[]);
    }    
    /**
     * Return the plan data provider (use yearly plan as representative)
     * @param type $plans
     * @return type
     */
    public function getPlanDataProvider($plans)
    {
        $rep = [];
        
        $yearlyPlan = false;
        foreach ($plans as $plan) {
            if ($plan['recurring']==Plan::YEARLY){
                $yearlyPlan = true;//There exists a yearly plan
            }
            if ($plan['recurring']==Plan::YEARLY || $plan['type']==Plan::FIXED){
                $rep[] = $plan;
                break;
            }
        }
        
        //for plan contains only monthly plan, pick up plan data again
        //such as Lite plan
        if (!$yearlyPlan && empty($rep)){
            foreach ($plans as $plan) {
                if ($plan['recurring']==Plan::MONTHLY){
                    $rep[] = $plan;
                    break;
                }
            }
        }
        
        return new CArrayDataProvider($rep, [
            'pagination'=>array(
                'pageSize'=>50,//need to set to higher value to have all plans in one page
                'currentPage'=>0,//page 1
            ),
        ]);
    } 
    /**
     * Return the plan item data provider
     * @param type $items
     * @return type
     */
    public function getPlanItemDataProvider($items)
    {
        return new CArrayDataProvider($items, [
            'sort'=>[
                'attributes'=>['id'],
            ],            
            'pagination'=>[
                'pageSize'=>1000,//need to set to higher value to have all items in one page
                'currentPage'=>0,//page 1
            ],
        ]);
    }    
    
}
