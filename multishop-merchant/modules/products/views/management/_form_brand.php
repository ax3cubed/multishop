<div style="margin-bottom:10px;margin-left:15px;">  
    <?php echo  Chtml::activeDropDownList(
                    $model,
                    'brand_id',
                    $model->getBrandsArray(user()->getLocale()),
                    array('prompt'=>'',
                          'class'=>'chzn-select',
                          'data-placeholder'=>Sii::t('sii','Select Brand'),
                          'style'=>'width:250px;')
                );
    ?>
</div>