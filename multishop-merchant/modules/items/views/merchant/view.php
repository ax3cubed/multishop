<?php $this->getModule()->registerGridViewCssFile();?>
<?php if ($model->actionable(user()->currentRole,user()->getId()))
        $this->getModule()->registerTaskScript();
?>
<?php
$this->breadcrumbs=array(
	Sii::t('sii','Purchase Orders')=>url('purchase-orders'),
	$model->order_no=>$model->order->viewUrl,
        Sii::t('sii','Purchased Item')
);
$workflowAction = $model->getWorkflowAction(user()->currentRole);
$this->menu=array(
      array('id'=>$workflowAction,'title'=>SButtonColumn::getButtonTitle($workflowAction),
            'subscript'=>SButtonColumn::getButtonSubscript($workflowAction), 'visible'=>$model->actionable(user()->currentRole,user()->getId()), 
            'linkOptions'=>array(
                'onclick'=>'qwi('.$model->id.',\''.$workflowAction.'\')',
                'class'=>'workflow-button'
        )),
);

$this->widget('common.widgets.spage.SPage',array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'superscript'=>!$model->hasShippingOrder?'':
            CHtml::link($model->shipping_order_no,$model->shippingOrder->viewUrl).
            CHtml::tag('span',['class'=>'tag'],Helper::htmlColorText($model->shippingOrder->getStatusText())),
    ),
    'sections'=>$this->getSectionsData($model),
    'csrfToken' => true, //needed by tasks.js
));
