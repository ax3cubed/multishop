<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.models.ShopSetting');
Yii::import('common.modules.messages.models.Message');
Yii::import('common.modules.plans.models.Feature');
Yii::import('common.modules.plans.models.Subscription');
/**
 * Description of InventoryCommand
 *
 * @author kwlok
 */
class InventoryCommand extends SCommand 
{
    protected $logFile;
    /**
     * Init
     */
    public function init() 
    {
        parent::init();
        $this->logFile = Yii::app()->runtimePath.DIRECTORY_SEPARATOR.'inventory_'.date('Ymd').'.log';
    }      
    /**
     * Scan inventories, and send notification when inventory level is low and below threshold
     * 
     * Steps:
     * [1]SELECT * FROM `s_shop_setting` WHERE notifications like '%lowInventory":"1%'
     * [2]from list of shops (online), select all products (online)
     * [3]from products, select into inventory level below threshold
     * 
     * @return int
     */
    public function actionScan() 
    {   
        $this->logInfo(__METHOD__.' start..');
        
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." ***** new scan *****\n", FILE_APPEND);
        
        try {
            
            foreach (ShopSetting::model()->lowInventoryEnabled()->findAll() as $setting) {
                
                if ($this->_allowReceiveAlert($setting->shop->account_id)){
                    $threshold = $setting->getValue(ShopSetting::$notifications,'lowInventoryThreshold');
                    $dataProvider = $this->_constructLowInventoryDataProvider($setting->shop_id,$threshold);
                    $this->_print("lowInventoryThreshold $threshold for shop = ".$dataProvider->shop_id." has ".$dataProvider->getItemsCount()." product(s) inventory running low.");
                    if ($dataProvider->hasItems()){
                        $this->logInfo(__METHOD__.' $dataProvider',$dataProvider);
                        $this->_print("start send notification..");
                        Yii::app()->serviceManager->getNotificationManager()->send($dataProvider);
                    }
                }
                else {
                    $this->_print("Skip shop $setting->shop_id as owner has no permission to receive alert");
                }
            }
            
            $this->_print("Completed");

            return 0;        
            
        } catch (CException $e) {
            $this->logError('error: '.$e->getTraceAsString());
            return -1;
        }
    }     
    
    private function _constructLowInventoryDataProvider($shop_id,$threshold)
    {
        $dataProvider = new LowInventoryDataProvider();
        $dataProvider->shop_id = $shop_id;
        $dataProvider->items = $this->_retrieveInventory($shop_id, $threshold);
        $dataProvider->shop_name = $dataProvider->getShopName();//have to call after items are loaded
        $dataProvider->locale = $dataProvider->getShopLocale();//have to call after items are loaded
        return $dataProvider;
    }
    
    private function _retrieveInventory($shop_id,$threshold)
    {        
        $command = Yii::app()->db->createCommand()
                    ->select('i.id, i.sku, s.name shop_name, s.language locale, p.name product_name, m.src_url image_url, i.quantity, i.available')             
                    ->from('s_inventory i')
                    ->join('s_shop s','s.id=i.shop_id AND s.status=\''.Process::SHOP_ONLINE.'\'')
                    ->join('s_product p','p.shop_id=i.shop_id AND p.status=\''.Process::PRODUCT_ONLINE.'\'')
                    ->join('s_image m','m.obj_type=\'s_product\' AND m.obj_id=p.id AND m.id=p.image')
                    ->where('i.obj_type=\'s_product\' AND i.obj_id=p.id AND i.shop_id='.$shop_id.' AND (i.available/i.quantity) <= '.$threshold);

        //$this->logTrace(__METHOD__.' query command = '.$command->text);
        
        $data = Yii::app()->db->createCommand($command->text)->queryAll();
        
        //$this->logTrace(__METHOD__.' data', $data);
        
        return $data;
    }
    
    private function _allowReceiveAlert($user)
    {
        $feature = Feature::getRecord(Feature::$receiveLowStockAlert);
        $command = Yii::app()->db->createCommand()
                    ->select('p.plan_id')             
                    ->from('s_plan_item p')
                    ->where('p.name=\''.$feature->toKey().'\'');

        $data = Yii::app()->db->createCommand($command->text)->queryAll();
        $plans = [];
        foreach ($data as $record) {
            $plans[] = $record['plan_id'];
        }
        $this->logTrace(__METHOD__.' plans', $plans);
        $subscription = $this->_findSubscription($user);
        if ($subscription==null){
            $this->logTrace(__METHOD__.' subscription not found for user '.$user);
            return false;
        }
        else {
            $this->logTrace(__METHOD__." checking subscription plan $subscription->plan_id for user $user");
            return in_array($subscription->plan_id, $plans);
        }
    }
    
    private function _findSubscription($user) 
    {
        return Subscription::model()->myPlans($user,Process::SUBSCRIPTION_ACTIVE)->notExpired()->find();
    }
    
    private function _print($message)
    {
        file_put_contents($this->logFile, date('m/d/Y h:i:s a')." $message"."\n", FILE_APPEND);
        $this->logInfo(__METHOD__.' '.$message);
    }
}
