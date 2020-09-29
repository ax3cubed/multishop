<div id="<?php echo $pageId;?>" class="shop-subpage <?php echo $pageId;?>">
    
    <?php if (isset($heading)):?>
        <h1><?php echo $heading;?></h1>
    <?php endif;?>
    
    <?php 
        if (isset($desc)) 
            echo CHtml::tag('div',['class'=>'desc'],$desc);
    ?>
    <?php echo $content;?>
</div>
<?php
if (isset($script)){
    Helper::registerJs($script,$pageId);
}   