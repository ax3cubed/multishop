<?php if (isset($showActionBar) && $showActionBar):?>
    <div class="action-bar">
        <span>
        <?php   
            if ($this->showLikeButton())
                $this->renderView('likes.buttonform',array('model'=>new LikeForm(get_class($data),$data->id),true));
            
            if ($data->likeCounter>0)
                echo CHtml::tag('span',array('class'=>'like-'.strtolower(get_class($data)).'-total-'.$data->id.' count'),$data->likeCounter);
        ?>
        <span style="padding-left:3px;">
        <?php
            echo CHtml::tag('span',array('class'=>'icon'),CHtml::link('<i class="fa fa-comment-o"></i>',$this->getCommentViewUrl($page,$data),['title'=>Sii::t('sii','Write a comment')]));
                    
            if ($data->commentCounter>0)
                echo CHtml::tag('span',array('class'=>'comment-'.strtolower(get_class($data)).'-total-'.$data->id.' count'),$data->commentCounter);
         ?>
        </span>
    </div>    
<?php endif;?>
