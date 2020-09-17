<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('shipping_order','shipping_order_items'),
    'htmlOptions'=>array('class'=>'shipping-order'),
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}"><div class="value">{value}</div></div>',
            'value'=>TaskBaseController::getWorkflowButtons($shippingOrder),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderPartial('_purchaseorder_so_summary',
                                          array('dataProvider'=>$shippingOrder->getOwnDataProvider()),
                                          true),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}"><div class="value">{value}</div></div>',
            'value'=>$this->renderPartial($this->module->getView('items'),array('dataProvider'=>$itemsDataProvider),true),
        ),
    ),
)); 
