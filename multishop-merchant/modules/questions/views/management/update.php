<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update Answer'),$model);

$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'viewUrl'=>$model->merchantViewUrl,
        'createVisible'=>false,
        'updateVisible'=>$model->answerUpdatable(),
        'activateUrl'=>url('questions/management/activate',array('Question[id]'=>$model->id)),
        'deactivateUrl'=>url('questions/management/deactivate',array('Question[id]'=>$model->id)),
    ));

$this->getPage(array(
    'id'=>'question_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=>'<i class="fa fa-quote-left"></i>'.Helper::purify($model->question).'<i class="fa fa-quote-right"></i>',
        'tag'=>$model->getStatusText(),
        'image'=>$model->questioner->getAvatar(Image::VERSION_XSMALL),
        'subscript'=>$model->formatDateTime($model->question_time,true).Helper::htmlColorText($model->getTypeLabel()),
    ),
    'body'=>$this->renderPartial('_update_body', array('model'=>$model),true),
));