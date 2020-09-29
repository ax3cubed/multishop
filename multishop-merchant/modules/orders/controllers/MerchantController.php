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
    use OrderControllerTrait;
    
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Orders');
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'ShippingOrder';
        $this->viewName = Sii::t('sii','Shipping Orders');
        $this->route = 'orders/merchant/index';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->searchMap = [
            'order_no' => 'order_no',
            'shipping_no' => 'shipping_no',
            'price' => 'grand_total',
            'shipping' => 'item_shipping',
            'payment_method' => 'payment_method',
            'date' => 'create_time',
            'shop' => 'shop_id',
            'items' => 'id',//attribute "id" is used as proxy to search into item names
        ];
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'ShippingOrderFilterForm';
        $this->filterFormHomeUrl = url('orders/merchant');  
        $this->filterFormQuickMenu = [
            ['id'=>'po','title'=>Sii::t('sii','View Purchase Orders'),'subscript'=>Sii::t('sii','PO'), 'url'=>url('purchase-orders')],
            ['id'=>'so','title'=>Sii::t('sii','View Shipping Orders'),'subscript'=>Sii::t('sii','SO'), 'url'=>url('orders'),'linkOptions'=>['class'=>'active']],
            ['id'=>'items','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items')],
        ];        
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            $this->serviceNotAvailableAction,
        ];
        //-----------------//   
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            'view'=>array(
                'class'=>'orders.actions.OrderViewAction',
                'model'=>$this->modelType,
                'finderMethod'=>'findOrder',
                'viewFilePO'=>'purchaseorder',
                'viewFileSO'=>'shippingorder',
            ),
            $this->serviceNotAvailableAction => array(
                'class'=>'common.modules.plans.actions.ServiceNotAvailableAction',
                'breadcrumbs'=>[
                    Sii::t('sii','Shipping Orders'),
                ],
                'pageHeading'=>Sii::t('sii','Shipping Orders'),
                'flashId'=>$this->modelType,
            ),            
        ));
    }    
    
    protected function getSectionsPOData($purchaseOrder) 
    {
        Yii::import('common.modules.tasks.views.workflow.sections.OrderSections');
        $sections = new OrderSections($this,$purchaseOrder);
        $sections->itemsView = $this->module->getView('items');
        
        //section: Order Refund Summary
        if ($purchaseOrder->orderRefunded()){
            $sections->add(array('id'=>'refund_summary',
                            'viewFile'=>'_summary_refund',
                            'viewData'=>array('model'=>$purchaseOrder)));
        }
        
        $sections->add(array('id'=>'po_summary',
                            'viewFile'=>'_purchaseorder_summary',
                            'viewData'=>array('model'=>$purchaseOrder)));
    
        //$sections = new CList();
        if (!empty($purchaseOrder->shippingOrders)){
            foreach($purchaseOrder->shippingOrders as $key => $shippingOrder){
                $itemViewParams = $this->_getDataProviderWithResetScope($shippingOrder->searchItems());//todo a trick to fix issue that model()->resetScope() seems not working
                //section 1: Shipping order
                $sections->add(array('id'=>'summary_'.$shippingOrder->id,
                                'name'=>Sii::t('sii','Shipping Order {shipping_no}',array('{shipping_no}'=>CHtml::link($shippingOrder->shipping_no,$shippingOrder->viewUrl))).
                                        CHtml::tag('span',['class'=>'tag'],Helper::htmlColorText($shippingOrder->getStatusText())),
                                'heading'=>true,'top'=>$key==0?true:false,
                                'viewFile'=>'_purchaseorder_so',
                                'viewData'=>array('shippingOrder'=>$shippingOrder,'itemsDataProvider'=>$itemViewParams)));
            }
        }
        else {//since shipping order is not created yet, need to show products here
            $sections->addProducts();
            $sections->addShippingMethod();
        }
        $sections->addShippingAddress();
        $sections->addPaymentRecord();
        $sections->addAttachments();
        $sections->addProcessHistory();
        return $sections->getData(false);//false = custom sections
        
    }
    
    protected function getSectionsData($model) 
    {
        Yii::import('common.modules.tasks.views.workflow.sections.ShippingOrderSections');
        $sections = new ShippingOrderSections($this,$model);
        $sections->itemsView = $this->module->getView('items');
        
        //section: Order Refund Summary
        if ($model->orderRefunded()){
            $sections->add(array('id'=>'refund_summary',
                            'viewFile'=>'_summary_refund',
                            'viewData'=>array('model'=>$model)));
        }
        //section: Order Summary
        $sections->add(array('id'=>'summary',
                            'viewFile'=>'_summary',
                            'viewData'=>['model'=>$model,]
                        ));

        if ($model->orderRefunded()){
            $sections->addShippingOrderTotal(true);
            //section: Refunded Products
            $refundedItems = $this->_getDataProviderWithResetScope($model->searchRefundedItems());
            $sections->add(array('id'=>'refund','name'=>Sii::t('sii','Refund Products'),'heading'=>true,
                                 'viewFile'=>$this->module->getView('items'),'viewData'=>array('dataProvider'=>$refundedItems)));
            $sections->addShippedProducts();
        }
        else {
            $sections->addProducts();
        }

        $sections->addShippingMethod($model->search());
        $sections->addShippingAddress();
        $sections->addPaymentRecord();
        $sections->addAttachments();
        $sections->addProcessHistory();
        return $sections->getData(false);//false = custom sections
    }      

    public function getSearchCriteria($model)
    {
        $criteria=new CDbCriteria;
        $criteria->compare('order_no',$model->order_no,true);
        $criteria->compare('shipping_no',$model->shipping_no,true);
        $criteria->compare('grand_total',$model->grand_total,true);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        $criteria = QueryHelper::parseJsonStringSearch($criteria, 'item_shipping', $model->item_shipping, '\"');
        $criteria = QueryHelper::parseJsonStringSearch($criteria, 'payment_method', $model->payment_method);
        //$criteria->compare('status', SQLHelper::parseStatusSearch($model->status));//already supported as scope filter in spageindex
        if (!empty($model->id))//attribute "id" is used as proxy to search into item names
            $criteria->addCondition($model->constructItemsInCondition($model->id,'merchant'));
        if (!empty($model->shop_id))
            $criteria->addCondition($model->constructShopsInCondition($model->shop_id,$this->modelFilter));

        return $criteria;
    }    
    /**
     * Below piece of code, beside logging for debug use, is used also as a tweak to solve a tricky bug
     * where $model->searchRefundedItems() is always not get displaying at $this->module->getView('items')?
     * and since overridden by following $model->searchShippedItems()
     * without it, the bug persists
     * 
     * //todo a trick to fix issue that model()->resetScope() seems not working
     */
    private function _getDataProviderWithResetScope($dataProvider)
    {
        foreach ($dataProvider->data as $data)
            logTrace(__METHOD__,$data->getAttributes());     
        return $dataProvider;
    }
    
    protected function getDiscountInfo($model)
    {
        $info = '';
        if ($model->hasCampaignSale())
            $info .= CHtml::tag('div',array('class'=>'discount'),$model->formatCurrency($model->getCampaignSaleDiscount()).$model->getCampaignSaleColorTag(user()->getLocale()));
        if ($model->hasCampaignPromocode())
            $info .= CHtml::tag('div',array('class'=>'discount'),$model->formatCurrency($model->getCampaignPromocodeDiscount()).$model->getCampaignPromocodeColorTag(user()->getLocale()));
        return $info;
    }
    
    protected function hasDiscountInfo($model)
    {
        return $model->hasCampaignSale()||$model->hasCampaignPromocode();
    }
}