<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of MerchantController
 *
 * @author kwlok
 */
class MerchantController extends SPageIndexController 
{
    use ItemControllerTrait;
    
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Items');
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Item';
        $this->modelFilter = 'merchant';
        $this->viewName = Sii::t('sii','Items');
        $this->route = 'items/merchant/index';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->searchMap = [
            'order_no' => 'order_no',
            'shipping_no' => 'shipping_order_no',
            'date' => 'create_time',
            'item' => 'name',
            'unit_price' => 'unit_price',
            'total_price' => 'total_price',
        ];        
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'ItemFilterForm';
        $this->filterFormHomeUrl = url('items/merchant');
        $this->filterFormQuickMenu = [
            array('id'=>'po','title'=>Sii::t('sii','View Purchase Orders'),'subscript'=>Sii::t('sii','PO'), 'url'=>url('purchase-orders')),
            array('id'=>'so','title'=>Sii::t('sii','View Shipping Orders'),'subscript'=>Sii::t('sii','SO'), 'url'=>url('shipping-orders')),
            array('id'=>'items','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items'),'linkOptions'=>['class'=>'active']),
        ];
    }    
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'view'=>[
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'loadModelMethod'=>'prepareModel',
                'pageTitleAttribute'=>'name',
            ],
        ]);
    } 
    
    public function prepareModel()
    {
        $orderNo = current(array_keys($_GET));//take the first key as order no
        if (!empty($_GET[$orderNo])){//has item id
            $model = Item::model()->merchant()->byOrderNo($orderNo, Helper::urlstrdetr($_GET[$orderNo]))->find();            
            if($model===null)
                throwError404(Sii::t('sii','The requested page does not exist'));  
            $model->setAccountOwner('shop');//refer to AccountBehavior
            return $model;
        }
        else
            throwError404(Sii::t('sii','The requested page does not exist'));    
    }    
    
    protected function getSectionsData($model) 
    {
        Yii::import('common.modules.tasks.views.workflow.sections.ItemSections');
        $sections = new ItemSections($this,$model);
        $sections->itemsView = '_items';
        
        if ($model->itemRefunded())
            $sections->addRefundSummary();
        $sections->addItems(false);
        $sections->addCommonViews();
        return $sections->getData(false);//false = custom sections        
    }          
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return CDbCriteria
     */
    public function getSearchCriteria($model)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('order_no',$model->order_no,true);
        $criteria->compare('shipping_order_no',$model->shipping_order_no,true);
        $criteria->compare('unit_price',$model->unit_price,true);
        $criteria->compare('total_price',$model->total_price,true);
        $criteria = QueryHelper::parseLocaleNameSearch($criteria, 'name', $model->name);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        //$criteria->compare('status', QueryHelper::parseStatusSearch($model->status));//already supported as scope filter in spageindex
        
        return $criteria;
    }    
}
