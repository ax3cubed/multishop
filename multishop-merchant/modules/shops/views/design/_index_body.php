<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'columns'=>[
        [
            ['label'=>Sii::t('sii','Current Theme'),'value'=>$model->getThemeName(user()->getLocale())],
            ['label'=>Sii::t('sii','Current Style'),'value'=>$model->getStyleName(user()->getLocale())],
        ],
        [
            ['label'=>'','type'=>'raw','value'=>CHtml::link(Sii::t('sii','Edit Theme'),$this->getThemeUpdateUrl($model->shop,['theme'=>$model->theme,'style'=>$model->style]),['id'=>'btn_update_theme','class'=>'ui-button'])],
        ],
    ],
]);
?>

<?php
$this->widget('common.widgets.SListView', [
    'dataProvider' => $model->dataProvider,
    'htmlOptions' => ['class'=>'themes-container'],
    'itemView' => '_theme',
    'viewData' => ['owner'=>$model->shop],
    'beforeAjaxUpdate'=>'function(id, data){ $(".page-loader").show(); }',//refer to shops.js, id is the above listview id
    'afterAjaxUpdate'=>'function(id, data){ enablethemebuttons();$(".page-loader").hide(); }',//refer to shops.js, id is the above listview id
]);
$script = <<<EOJS
enablethemebuttons();
EOJS;
Helper::registerJs($script);
?>

<div class="tips">
    <h4 class="themes-legend"><?php echo Sii::t('sii','Legend');?></h4>
    <div>
        <button class="ui-button current"></button> <?php echo Sii::t('sii','Theme that is currently used by shop');?>
    </div>
    <div>
        <button class="ui-button publish"></button> <?php echo Sii::t('sii','Themes that you have installed or purchased');?>
    </div>
    <div>
        <button class="ui-button"></button> <?php echo Sii::t('sii','Themes that you can get free or buy');?>
    </div>
</div>
