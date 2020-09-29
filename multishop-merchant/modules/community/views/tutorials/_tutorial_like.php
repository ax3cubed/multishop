<div class="like-counter-wrapper">
    <span class="like-total" style="display:<?php echo $likeForm->counter>0?'inline':'none';?>">
        <span class="like-<?php echo strtolower($likeForm->type);?>-total-<?php echo $likeForm->target;?>"><?php echo Sii::t('sii','n<=1#{n} Like|n>1#{n} Likes',[$likeForm->counter]);?></span>
    </span>
    <div class="like-button">
        <?php $this->renderView('likes.buttonform',array('model'=>$likeForm));?>
    </div>
</div>
