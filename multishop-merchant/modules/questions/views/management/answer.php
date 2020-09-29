<?php $this->getModule()->registerFormCssFile();?>
<?php
//$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Answer'),$model);
//Note: Call via runControllerMethod() as the view might be rendered from tasks/question/answer when error is hit
$this->breadcrumbs = $this->module->runControllerMethod('questions/management','getBreadcrumbsData',Sii::t('sii','Answer'),$model);
            
$pageParams = [
    'id'=>'answer_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'flash'  => [get_class($model),'AnswerForm'],
    'heading'=> [
        'name'=>'<i class="fa fa-quote-left"></i>'.Helper::purify($model->question).'<i class="fa fa-quote-right"></i>',
        'image'=>$model->questioner->getAvatar(Image::VERSION_XSMALL),
        'subscript'=>$model->formatDatetime($model->question_time,true).Helper::htmlColorText($model->getTypeLabel()),
    ],
    'body'=>$this->renderPartial($model->formView, ['model'=>$model],true),
];

//$this->getPage($pageParams);
//Note: Call via runControllerMethod() as the view might be rendered from tasks/question/answer when error is hit
$this->module->runControllerMethod('questions/management','getPage',$pageParams);