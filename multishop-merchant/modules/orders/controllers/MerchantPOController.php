<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of MerchantPOController
 *
 * @author kwlok
 */
class MerchantPOController extends SPageIndexController 
{ 
    use OrderControllerTrait;
    
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Order';
        $this->viewName = Sii::t('sii','Purchase Orders');
        $this->route = 'orders/merchantPO/index';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->modelFilter = 'merchant';
        $this->searchMap = [
            'order_no' => 'order_no',
            'price' => 'grand_total',
            'date' => 'create_time',
            'shop' => 'shop_id',
            'items' => 'remarks',//attribute "remarks" is used as proxy to search into item names
            'shipping' => 'item_shipping',
            'payment_method' => 'payment_method',
        ];
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'OrderFilterForm';
        $this->filterFormHomeUrl = url('orders/merchantPO');
        $this->filterFormQuickMenu = [
            ['id'=>'po','title'=>Sii::t('sii','View Purchase Orders'),'subscript'=>Sii::t('sii','PO'), 'url'=>url('purchase-orders'),'linkOptions'=>['class'=>'active']],
            ['id'=>'so','title'=>Sii::t('sii','View Shipping Orders'),'subscript'=>Sii::t('sii','SO'), 'url'=>url('shipping-orders')],
            ['id'=>'items','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items')],
        ];
        //-----------------//   
        $this->rightsFilterActionsExclude = [
            $this->serviceNotAvailableAction,
        ];        
    }   
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            $this->serviceNotAvailableAction => [
                'class'=>'common.modules.plans.actions.ServiceNotAvailableAction',
                'breadcrumbs'=>[
                    Sii::t('sii','Purchase Orders'),
                ],
                'pageHeading'=>$this->viewName,
                'flashId'=>$this->modelType,
            ],                
        ]);
    }          
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return CDbCriteria
     */
    public function getSearchCriteria($model)
    {
        $criteria=new CDbCriteria;
        $criteria->compare('order_no',$model->order_no,true);
        $criteria->compare('grand_total',$model->grand_total,true);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        $criteria = QueryHelper::parseJsonStringSearch($criteria, 'item_shipping', $model->item_shipping, '\"');
        $criteria = QueryHelper::parseJsonStringSearch($criteria, 'payment_method', $model->payment_method);
        if (!empty($model->remarks))//attribute "remarks" is used as proxy to search into item names
            $criteria->addCondition($model->constructItemsInCondition($model->remarks,$this->modelFilter));
        if (!empty($model->shop_id))
            $criteria->addCondition($model->constructShopsInCondition($model->shop_id,$this->modelFilter));
        return $criteria;
    }
}
