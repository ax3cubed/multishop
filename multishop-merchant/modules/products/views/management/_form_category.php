<div style="margin-bottom:10px;margin-left:15px;">   
    <?php   echo CHtml::activeDropDownList(
                    $model,
                    'categories',
                    $model->getCategoriesArray(user()->getLocale()),
                    array(
                        'prompt'=>'',
                        'multiple'=>true,
                        'encode'=>false,
                        'class'=>'chzn-select',
                        'data-placeholder'=>Sii::t('sii','Select Category'),
                        'style'=>'width:100%;')
                );
    ?>
</div>

