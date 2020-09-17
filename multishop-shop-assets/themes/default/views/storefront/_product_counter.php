<div class="product-counter">
    <?php if (isset($shareUrl)):?>
    <div class="facebook-share-button" style="display:inline;">
        <?php $this->widget('FacebookShareButton',['url'=>$shareUrl]);?>
    </div>
    <?php endif;?>
    <div class="comment-counter-wrapper" style="display:<?php echo $commentForm->counter>0?'inline-block':'none';?>">
        <i class="fa fa-comment-o"></i>
        <span class="<?php echo $commentForm->id;?> comment-total">
            <span id="<?php echo $commentForm->id;?>-comment-total">
                <?php echo $commentForm->counter;?>
            </span>
        </span>
    </div>
    <div class="like-counter-wrapper">
        <?php if (isset($showLikeButton)&&$showLikeButton):?>
        <div class="like-button">
            <?php $this->renderView('likes.buttonform',array('model'=>$likeForm));?>
        </div>
        <?php endif; ?>
        <span class="like-total" style="display:<?php echo $likeForm->counter>0?'inline':'none';?>">
            <span class="like-<?php echo strtolower($likeForm->type);?>-total-<?php echo $likeForm->target;?>">
                <?php   if ($likeForm->modal)
                            echo Sii::t('sii','n<=1#{n} Like|n>1#{n} Likes',[$likeForm->counter]);
                        else
                            echo $likeForm->counter;
                ?>
            </span>
        </span>
        
    </div>
    <?php $this->widget('common.widgets.SListView', array(
                                'id'=>'fan_list',
                                'dataProvider'=>$likeForm->searchTargets(),
                                'template'=>'{items}',
                                'emptyText'=>'',
                                'itemView'=>$this->module->getView('likes.fan'),
                    ));   
    ?>
</div>   
