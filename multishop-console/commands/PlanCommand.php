<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('console.components.CommandLogFileTrait');
Yii::import('common.modules.plans.models.*');
Yii::import('common.modules.news.models.*');
/**
 * Description of PlanCommand
 *
 * @author kwlok
 */
class PlanCommand extends SCommand 
{
    use CommandLogFileTrait {
        init as traitInit;
    }          
    public $migration;
    public $currency = 'SGD';
    /**
     * Init
     */
    public function init() 
    {
        $this->logFileName = 'plan';
        $this->traitInit();
        $this->migration = new PlanMigration(); 
        $this->migration->init($this);
    } 
    /**
     * Install package
     * 
     * Usage:
     * Run `php console plan install --package=id [--featureFile=/path/to/json]
     * 
     * Steps:
     * [1] Install with Package id = 0 first. This is to do init setup
     * [2] Install any other target packages (with defined package id)
     * 
     * @param type $package The package id
     * @param type $featureFile The file path contains all the features set for the package; 
     * 
     * @see Sample features-file at repo multishop-admin/modules/plans/models/features
     */
    public function actionInstall($package,$featureFile=null)
    {
        $this->migration->install($package,$featureFile);
    }
    /**
     * Uninstall package
     * 
     * Usage:
     * Run `php console plan uninstall --package=id 
     * 
     * Steps:
     * [1] Uninstall any other target packages (with defined package id)
     * [2] UnInstall with Package id = 0 at last. This is to do clean up
     * 
     * @param type $package The package id
     */    
    public function actionUninstall($package)
    {
        $this->migration->uninstall($package);
    }
}

/**
 * Description of PlanMigration
 *
 * @author kwlok
 */
class PlanMigration extends CDbMigration 
{    
    protected $command;
    protected $freeTrialPackage;
    protected $freePackage;
    protected $litePackage;
    protected $standardPackage;
    protected $plusPackage;
    protected $enterprisePackage;
    protected $customPackage;
    /*
     * The target installed package
     */
    protected $targetPackage;
    /*
     * The target feature set for installing package; It should be a json file returning array of target features
     */
    protected $targetFeatureFile;
    
    public function init($command)
    {
        $this->command = $command;
        //instantiate FreeTrial
        $freeTrialPlan = new FreeTrial();
        $freeTrialPlan->currency = $this->command->currency;
        $this->freeTrialPackage = new FreeTrialPackage();
        $this->freeTrialPackage->migration = $this;
        $this->freeTrialPackage->addPlan($freeTrialPlan);
        //instantiate Free plan
        $freePlan = new FreePlan();
        $freePlan->currency = $this->command->currency;
        $this->freePackage = new FreePackage();
        $this->freePackage->migration = $this;
        $this->freePackage->addPlan($freePlan);
        //instantiate Lite
        $litePlan = new LitePlan();
        $litePlan->currency = $this->command->currency;
        $this->litePackage = new LitePackage();
        $this->litePackage->migration = $this;
        $this->litePackage->addPlan($litePlan);
        $yearlyPlanMonthsDiscount = 1;//1 month discount
        //instantiate Standard
        $stdPlanMonthly = new StandardMonthlyPlan();
        $stdPlanMonthly->currency = $this->command->currency;
        $stdPlanYearly = new StandardYearlyPlan();
        $stdPlanYearly->currency = $this->command->currency;
        $stdPlanYearly->price = $stdPlanMonthly->price * (12 - $yearlyPlanMonthsDiscount);
        $this->standardPackage = new StandardPackage();
        $this->standardPackage->migration = $this;
        $this->standardPackage->addPlan($stdPlanMonthly);        
        $this->standardPackage->addPlan($stdPlanYearly);        
        //instantiate Plus
        $plusPlanMonthly = new PlusMonthlyPlan();
        $plusPlanMonthly->currency = $this->command->currency;
        $plusPlanYearly = new PlusYearlyPlan();
        $plusPlanYearly->currency = $this->command->currency;
        $plusPlanYearly->price = $plusPlanMonthly->price * (12 - $yearlyPlanMonthsDiscount);
        $this->plusPackage = new PlusPackage();
        $this->plusPackage->migration = $this;
        $this->plusPackage->addPlan($plusPlanMonthly);        
        $this->plusPackage->addPlan($plusPlanYearly);        
        //instantiate Enterprise
        $entPlanMonthly = new EnterpriseMonthlyPlan();
        $entPlanMonthly->currency = $this->command->currency;
        $entPlanYearly = new EnterpriseYearlyPlan();
        $entPlanYearly->currency = $this->command->currency;
        $entPlanYearly->price = $entPlanMonthly->price * (12 - $yearlyPlanMonthsDiscount);
        $this->enterprisePackage = new EnterprisePackage();
        $this->enterprisePackage->migration = $this;
        $this->enterprisePackage->addPlan($entPlanMonthly);        
        $this->enterprisePackage->addPlan($entPlanYearly);       
        //instantiate Custom plan
        $customPlan = new CustomPlan();
        $customPlan->currency = $this->command->currency;
        $this->customPackage = new CustomPackage();
        $this->customPackage->migration = $this;
        $this->customPackage->addPlan($customPlan);
        
    }
    
    public function install($package,$featureFile=null)
    {
        $this->targetPackage = $package;
        $this->targetFeatureFile = $featureFile;
        
        $this->command->writeLog(__METHOD__);
        if ($this->targetPackage==0 && Feature::model()->count() > 0) {
            $this->command->writeLog('There are already features installed! Action not allowed.');
            return;
        }
        
        if ($this->targetPackage>0 && !file_exists($featureFile)){
            $this->command->writeLog("Error! Feature file $featureFile does not exist. ");
            return;
        }
        
        $this->safeUp();
        $this->clearPackageCache();
    }
    
    public function uninstall($package)
    {
        $this->targetPackage = $package;
        $this->command->writeLog(__METHOD__);
        if ($this->targetPackage==0 && Subscription::model()->active()->count() > 0) {
            $this->command->writeLog('There are active subscriptions! Action not allowed.');
            return;
        }
        $this->command->writeLog('Safe to uninstall...');
        $this->safeDown();
        $this->clearPackageCache();
    }

    public function safeUp()
    {
        switch ($this->targetPackage) {
            case 0://Init setup; IMPORTANT: only can run for one time
                $this->migrateRbacRuleUp();
                $this->migrateFeatureUp();
                break;
            case Package::FREE_TRIAL:
                $this->freeTrialPackage->loadFeatures($this->targetFeatureFile);
                $this->freeTrialPackage->migrateUp();
                break;
            case Package::FREE:
                $this->freePackage->loadFeatures($this->targetFeatureFile);
                $this->freePackage->migrateUp();
                break;
            case Package::LITE:
                $this->litePackage->loadFeatures($this->targetFeatureFile);
                $this->litePackage->migrateUp();
                break;
            case Package::STANDARD:
                $this->standardPackage->loadFeatures($this->targetFeatureFile);
                $this->standardPackage->migrateUp();
                break;
            case Package::PLUS:
                $this->plusPackage->loadFeatures($this->targetFeatureFile);
                $this->plusPackage->migrateUp();
                break;
            case Package::ENTERPRISE:
                $this->enterprisePackage->loadFeatures($this->targetFeatureFile);
                $this->enterprisePackage->migrateUp();
                break;
            case Package::CUSTOM:
                $this->customPackage->loadFeatures($this->targetFeatureFile);
                $this->customPackage->migrateUp();
                break;
            default:
                break;
        }

        return true;
    }
    
    public function safeDown()
    {
        switch ($this->targetPackage) {
            case 0://Init setup; IMPORTANT: only can run for one time
                $this->migrateFeatureDown();
                $this->migrateRbacRuleDown();
                break;
            case Package::FREE_TRIAL:
                $this->freeTrialPackage->migrateDown();
                break;
            case Package::FREE:
                $this->freePackage->migrateDown();
                break;
            case Package::LITE:
                $this->litePackage->migrateDown();
                break;
            case Package::STANDARD:
                $this->standardPackage->migrateDown();
                break;
            case Package::PLUS:
                $this->plusPackage->migrateDown();
                break;
            case Package::ENTERPRISE:
                $this->enterprisePackage->migrateDown();
                break;
            case Package::CUSTOM:
                $this->customPackage->migrateDown();
                break;
            default:
                break;
        }        
        
        return true;
    }
    
    public function migrateRbacRuleUp()
    {
        $this->insert('s_rbac_rule', [
            'name'=>'SubscriptionRule',
            'data'=>'O:36:"app\components\rbac\SubscriptionRule":3:{s:4:"name";s:16:"SubscriptionRule";s:9:"createdAt";i:1454398312;s:9:"updatedAt";i:1454398312;}',
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
        $this->insert('s_rbac_rule', [
            'name'=>'SubscriptionUpperLimitRule',
            'data'=>'O:46:"app\components\rbac\SubscriptionUpperLimitRule":3:{s:4:"name";s:26:"SubscriptionUpperLimitRule";s:9:"createdAt";i:1454383931;s:9:"updatedAt";i:1454383931;}',
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
        $this->insert('s_rbac_rule', [
            'name'=>'StorageUpperLimitRule',
            'data'=>'O:41:"app\components\rbac\StorageUpperLimitRule":5:{s:4:"name";s:21:"StorageUpperLimitRule";s:12:"subscription";N;s:8:"planItem";N;s:9:"createdAt";i:1476428854;s:9:"updatedAt";i:1476428854;}',
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
    }
    
    public function migrateRbacRuleDown()
    {
        $this->delete('s_rbac_rule');
    }
    
    public function migrateFeatureUp()
    {        
        foreach (Feature::arrayDataProvider() as $feature) {
            $this->insert('s_feature', [
                'id'=>$feature['id'],
                'group'=>$feature['group'],
                'name'=>$feature['name'],
                'params'=>$feature['params'],
            ]);
            $this->insert('s_rbac_item', [
                'name'=>$this->getFeatureKey($feature['id'], $feature['name'], $feature['group']),
                'type'=>2,
                'description'=>null,
                'rule_name'=>$feature['rule'],
                'data'=>null,
                'created_at'=>time(),
                'updated_at'=>time(),
            ]);
        }
    }
    
    public function migrateFeatureDown()
    {
        $this->delete('s_feature');
        $this->delete('s_rbac_item','type=2');
    }
        
    public function getFeatureKey($id,$name,$group)
    {
        return $id.Feature::KEY_SEPARATOR.$name.Feature::KEY_SEPARATOR.$group;
    }

    protected function clearPackageCache()
    {
        Yii::app()->commonCache->delete(SCache::PUBLISHED_PACKAGES);        
    }
}

class MigratingPlan extends Plan 
{
    public $featurePoints = [];
    public $migration;
    
    public function migrateUp()
    {
        $this->migration->insert('s_plan', [
            'id'=>$this->id,
            'account_id'=>$this->account_id,
            'name'=>$this->name,
            'type'=>$this->type,
            'duration'=>$this->duration, 
            'recurring'=>$this->recurring, 
            'price'=>$this->price, 
            'currency'=>$this->currency,
            'status'=>$this->status,
            'create_time'=>time(),
            'update_time'=>time(),
        ]);
        $this->migration->insert('s_rbac_item', [
            'name'=>$this->name,
            'type'=>1,
            'description'=>null,
            'rule_name'=>null,
            'data'=>null,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);         
        //[3]Create plan items and rbac
        foreach (Feature::arrayDataProvider() as $feature) {
            if (in_array($feature['name'],$this->featurePoints)){
                $featureKey = $this->migration->getFeatureKey($feature['id'], $feature['name'], $feature['group']);
                $this->migration->insert('s_plan_item', [
                    //'id'=>null,//let it auto increment
                    'plan_id'=>$this->id,
                    'name'=>$featureKey,
                    'create_time'=>time(),
                    'update_time'=>time(),
                ]);     
                $this->migration->insert('s_rbac_item_child', [
                    'parent'=>$this->name,
                    'child'=>$featureKey,
                ]);     
            }
        }
    }    
    /**
     * Tear off 
     */
    public function migrateDown()
    {
        $this->migration->delete('s_plan_item','plan_id='.$this->id);
        $this->migration->delete('s_plan','id='.$this->id);
        //$this->delete('s_rbac_item_child','parent=\''.$this->name.'\'');//cascade delete from s_rbac_tiem
        $this->migration->delete('s_rbac_item','name=\''.$this->name.'\'');
    }    
    
    public function hasFeatures()
    {
        return !empty($this->featurePoints);
    }

    public function setFeatures($features)
    {
        $this->featurePoints = $features;
    }
        
}

class MigratingPackage extends Package 
{
    public $planObjects = [];
    public $packageFeatures = [];
    public $migration;
    const TRUE  = 1;
    const FALSE = 0;
    
    public function addPlan($plan)
    {
        $this->planObjects[] = $plan;
    }
    /**
     * Load features from json file and is shared by all plans under the package
     * @param type $featureFile Expect to be php file (returning array)
     */
    public function loadFeatures($featureFile)
    {
        $this->packageFeatures = include $featureFile;
    }

    public function migrateUp()
    {
        $plans = [];
        foreach ($this->planObjects as $plan) {
            $plans[] = $plan->id;
        }
        $this->plans = implode(',', $plans);
        $this->migration->insert('s_package', [
            'id'=>$this->id,
            'account_id'=>$this->account_id,
            'name'=>$this->name,
            'type'=>$this->type,
            'plans'=>$this->plans,
            'params'=>json_encode($this->params),
            'status'=>$this->status,
            'create_time'=>time(),
            'update_time'=>time(),
        ]);        
        foreach ($this->planObjects as $plan) {
            $plan->migration = $this->migration;
            $plan->setFeatures($this->packageFeatures);//Features set is shared by all plans under the package
            $plan->migrateUp();
        }
    }
    /**
     * Tear off 
     */
    public function migrateDown()
    {
        $this->migration->delete('s_package','id='.$this->id);
        foreach ($this->planObjects as $plan) {
            $plan->migration = $this->migration;
            $plan->migrateDown();
        }        
    }    
}

class FreeTrialPackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::FREE_TRIAL;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::TRIAL;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::TRUE,
        ];
    }        
}

class FreeTrial extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::FREE_TRIAL;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::FREE_TRIAL);
        $this->type = Plan::TRIAL;
        $this->duration = 30;
        $this->recurring = null;
        $this->currency = null;
        $this->price = 0.00;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}

class FreePackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::FREE;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::FIXED;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::TRUE,
            Package::$showPricing=>self::FALSE,
            Package::$showButton=>self::TRUE,
        ];
    }        
}

class FreePlan extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::FREE;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::FREE);
        $this->type = Plan::FIXED;
        $this->recurring = null;
        $this->currency = null;
        $this->price = 0.00;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}

class LitePackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::LITE;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::RECURRING;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::FALSE,
            Package::$showPricing=>self::TRUE,
            Package::$showButton=>self::TRUE,
        ];
    }        
}

class LitePlan extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::LITE;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::LITE);
        $this->type = Plan::RECURRING;
        $this->duration = null;
        $this->recurring = Plan::MONTHLY;//no yearly plan is supported
        $this->currency = null;
        $this->price = 10.00;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}

class StandardPackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::STANDARD;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::RECURRING;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::FALSE,
            Package::$showPricing=>self::TRUE,
            Package::$showButton=>self::TRUE,
        ];
    }        
}

/**
 * Standard base plan
 */
class StandardBasePlan extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = null;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::STANDARD);
        $this->type = Plan::RECURRING;
        $this->duration = null;
        $this->recurring = null;
        $this->currency = null;
        $this->price = null;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}

class StandardMonthlyPlan extends StandardBasePlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::STANDARD_MONTHLY;
        $this->name = $this->name.' Monthly';
        $this->recurring = Plan::MONTHLY;
        $this->price = 20.00;
    }    
}

class StandardYearlyPlan extends StandardBasePlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::STANDARD_YEARLY;
        $this->name = $this->name.' Yearly';
        $this->recurring = Plan::YEARLY;
        //price to be set based on discount rate of monthly plan
    }    
}

class PlusPackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::PLUS;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::RECURRING;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::FALSE,
            Package::$showPricing=>self::TRUE,
            Package::$showButton=>self::TRUE,
        ];
    }        
}

/**
 * Pro base plan
 */
class PlusBasePlan extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = null;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::PLUS);
        $this->type = Plan::RECURRING;
        $this->duration = null;
        $this->recurring = null;
        $this->currency = null;
        $this->price = null;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}

class PlusMonthlyPlan extends PlusBasePlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::PLUS_MONTHLY;
        $this->name = $this->name.' Monthly';
        $this->recurring = Plan::MONTHLY;
        $this->price = 40.00;
    }    
}

class PlusYearlyPlan extends PlusBasePlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::PLUS_YEARLY;
        $this->name = $this->name.' Yearly';
        $this->recurring = Plan::YEARLY;
        //price to be set based on discount rate of monthly plan
    }    
}

class EnterprisePackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::ENTERPRISE;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::RECURRING;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::FALSE,
            Package::$showPricing=>self::FALSE,
            Package::$showButton=>self::FALSE,
        ];
    }        
}
/**
 * Enterprise base plan
 */
class EnterpriseBasePlan extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = null;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::ENTERPRISE);
        $this->type = Plan::RECURRING;
        $this->duration = null;
        $this->recurring = null;
        $this->currency = null;
        $this->price = null;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}

class EnterpriseMonthlyPlan extends EnterpriseBasePlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::ENTERPRISE_MONTHLY;
        $this->name = $this->name.' Monthly';
        $this->recurring = Plan::MONTHLY;
        $this->price = 99.00;
    }    
}

class EnterpriseYearlyPlan extends EnterpriseBasePlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::ENTERPRISE_YEARLY;
        $this->name = $this->name.' Yearly';
        $this->recurring = Plan::YEARLY;
        //price to be set based on discount rate of monthly plan
    }    
}

class CustomPackage extends MigratingPackage 
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Package::CUSTOM;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName($this->id);
        $this->type = Plan::CONTRACT;
        $this->plans = null;
        $this->status = Process::PACKAGE_DRAFT;
        $this->params = [
            Package::$businessReady=>self::TRUE,
            Package::$showPricing=>self::FALSE,
            Package::$showButton=>self::TRUE,
        ];
    }        
}

class CustomPlan extends MigratingPlan
{
    /**
     * Init model
     */
    public function init()
    {
        parent::init();
        $this->id = Plan::CUSTOM;
        $this->account_id = Account::SUPERUSER;
        $this->name = Package::siiName(Package::CUSTOM);
        $this->type = Plan::CONTRACT;
        $this->recurring = null;
        $this->currency = null;
        $this->price = 0.00;
        $this->status = Process::PLAN_APPROVED;
        $this->featurePoints = [
            //Any customized feature set, or to be loaded from feature file (<plan>_features.php)
        ];
    }    
}