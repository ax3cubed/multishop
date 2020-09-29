<div class="product-thumbnail">
    <span class="caption"><?php echo Helper::htmlColorText($data->getStatusText()); ?></span>
    <?php echo CHtml::link(
            $data->getImageThumbnail(Image::VERSION_SMALL,array('class'=>'img','title'=>$data->displayLanguageValue('name',user()->getLocale()))),
            $data->viewUrl);
    ?>    
</div>
