<?php
/* @var $this BgaController */
/* @var $data CampaignBga */
?>
<div class="list-box float-image">
    <span class="status">
        <?php echo ($data->hasExpired()?$data->getExpiredTag():'').
                   Helper::htmlColorText($data->getStatusText(),false); 
        ?>
    </span>
    <div class="image">
        <?php echo $data->getImageThumbnail(Image::VERSION_SMEDIUM);?>
    </div>    
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->displayLanguageValue('name',user()->getLocale())), $data->viewUrl),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('shop_id')).'</strong>'.
                             CHtml::link(CHtml::encode($data->shop->displayLanguageValue('name',user()->getLocale())), $data->shop->viewUrl),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>$this->renderPartial('_view_banner',array('model'=>$data),true),
                ),        
            ),
        )); 
    ?> 
</div>