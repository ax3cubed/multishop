<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.media.models.AttachmentForm");
Yii::import("common.modules.plans.models.SubscriptionPlan");
/**
 * Description of ProductImportForm
 *
 * [1] Support additional upload attribute 'filepath' specific to model Attachment
 * 
 * @author kwlok
 */
class ProductImportForm extends AttachmentForm
{
    /*
     * Local property
     */
    public $filepath;//the file path of import file
    /*
     * Below are configuration for parent class 
     */
    private $_processor;//import file processor
    public $group = 'ProductImport';//attachments grouping per object
    public $disableDescription = true;//If set to "true", the field will not be shown in form
    public $downloadView = 'merchant.modules.products.views.management._import_postupload';    
    /**
     * Initializes this model.
     */
    public function init()
    {
        parent::init();
        $this->maxSizeAllowed = 2000000;//max size 2M = 2000000 bytes
        $this->mimeTypesAllowed = 'application/vnd.ms-excel application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }        
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            //called at ProductImportAction::beforeReturn
            ['filepath', 'ruleMaximumRecords','on'=>$this->scenarioSkipSecureFileNames],
        ]);
    }
    
    public function ruleMaximumRecords($attribute,$params)
    {
        if ($this->fileProcessor->count > ProductImportManager::$maximumImport)
            $this->addError('filepath',Sii::t('sii','Maximum {count} products only are allowed per file import.',array('{count}'=>ProductImportManager::$maximumImport)));
        
        //check products count currently owned by the shop exceeds product limit 
        //todo Move this logic to use api (but need new api to support query feature limit)
        $shopModel = Shop::model()->findByPk($this->obj_id);
        if ($shopModel!=null && $shopModel->hasSubscription){
            
            $planItem = SubscriptionPlan::findItem($shopModel->subscription,'hasProductLimitTier');
            $limit = SubscriptionPlan::findItemParam($planItem, Feature::$upperLimit);
            if ($limit == Feature::$unlimited){
                logInfo(__METHOD__.' unlimited product import!');
                return;
            }
            elseif ($limit==null){
                logError(__METHOD__.' product import limit is null!',$shopModel->subscription->attributes);
                $this->addError('filepath',Sii::t('sii','Product import disallowed. Please check your subscription plan. If problem persists, please contact raise support ticket.'));
                return;
            }
            
            $preImportCount = Product::model()->locateShop($this->obj_id)->all()->count();
            $postImportCount = $preImportCount + $this->fileProcessor->count;
            if ($postImportCount > $limit){
                logError(__METHOD__." post import product count: $postImportCount exceeds product limit: $limit");
                $this->addError('filepath',Sii::t('sii','Total products after import exceeds product limit: {limit}. Please try to reduce the number of products in file import.',array('{limit}'=>$limit)));
            }
            
        }
    }
    
    public function getUploadButtonText($multiple)
    {
        return SButtonColumn::getButtonIcon('import').' '.Sii::t('sii','1#Upload Files|0#Upload File', $multiple);
    }    

    public function getFileProcessor()
    {
        if (!isset($this->_processor)){
            $this->_processor = new ProductImportManager(user()->getId(), $this->obj_id, $this->filepath);
        }
        return $this->_processor;
    }
    
}