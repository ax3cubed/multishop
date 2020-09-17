<h2><?php echo Sii::t('sii','Select Theme');?></h2>
<?php
$this->widget('common.widgets.SListView', [
    'dataProvider' => $model->dataProvider,
    'htmlOptions' => ['class'=>'themes-container'],
    'itemView' => '_theme',
    'viewData' => ['owner'=>$model->owner],
    'beforeAjaxUpdate'=>'function(id, data){ $(".page-loader").show(); }',//refer to shops.js, id is the above listview id
    'afterAjaxUpdate'=>'function(id, data){ enablethemebuttons();$(".page-loader").hide(); }',//refer to shops.js, id is the above listview id
]);
$script = <<<EOJS
enablethemebuttons();
EOJS;
Helper::registerJs($script);
    