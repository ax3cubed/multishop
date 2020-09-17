<div class="info-portlet">
    <div id="like-shop" class="like-form">
        <?php $this->renderView('likes.buttonform',array('model'=>$likeForm));?>
        <span class="like-shop-total">
            <?php echo $likeForm->counter;?>
        </span>
    </div>
    <div class="fan-list-wrapper">
        <?php $this->widget('common.widgets.SListView', array(
                                'id'=>'fan_list',
                                'dataProvider'=>$likeForm->searchTargets(),
                                'template'=>'{items}',
                                'emptyText'=>'',
                                'itemView'=>$this->module->getView('likes.fan'),
                        ));        
        ?>
    </div>
</div>
<div class="facebook-share-button">
<?php if (isset($shareUrl)):?>
    <?php $this->widget('FacebookShareButton',['url'=>$shareUrl]);?>
<?php endif;?>
</div>
