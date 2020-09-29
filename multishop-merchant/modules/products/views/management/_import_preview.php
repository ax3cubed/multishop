<div class="product-preview-container">
    <div>
        <span class="title"><?php echo Sii::t('sii','Preview');?></span>
        <span class="summary"style="float:right;"><?php echo Sii::t('sii','Total <span style="color:red;">{count}</span> Products',array('{count}'=>$totalCount));?></span>
    </div>
<?php
    $this->widget('common.widgets.SGridView', array(
        'id'=>'product_import_preview',
        'htmlOptions'=>array(
            'class'=>'grid-view products-import-preview',
            'data-file'=>$filename,
            'data-flash'=>$flashErrors,
            'data-total-columns'=>ProductImportColumn::getCount(),
            'data-error-icon'=>ProductImportColumn::getErrorIcon(),
            'data-ok-icon'=>ProductImportColumn::getOKIcon(),
        ),
        'template'=>'{items}{pager}',
        'dataProvider'=>$dataProvider,
        'columns'=>$columns,
    ));

    $this->widget('zii.widgets.jui.CJuiButton',array(
            'name'=>'importButton',
            'buttonType'=>'button',
            'caption'=> Sii::t('sii','Confirm'),
            'value'=>'actionBtn',
            'onclick'=>'js:function(){confirmproductimport();}',//actual loading of onclick is done at _import_postupload.php
            'htmlOptions'=>array('style'=>'margin-top:20px;'),
        ));
?>
</div>
