<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);
$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'activateUrl'=>url('campaigns/promocode/activate',array(get_class($model).'[id]'=>$model->id)),
        'deactivateUrl'=>url('campaigns/promocode/deactivate',array(get_class($model).'[id]'=>$model->id)),
    ));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->code,
        'subscript'=> $model->getCampaignText(user()->getLocale()),
        'tag'=> $model->getStatusText(),
    ),
    'description'=>Sii::t('sii','Campagin with status {online} means it is live and every one can enjoy the offer.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
));
