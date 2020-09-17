<div class="action shortcut">
    <?php
//TODO NOT YET SUPPORTED
//To support BUY NOW at shortcut bar, there are two ways:
//[1] Buyers able to change product options, shippign etc after clicking BUY NOW button. But right now cart page does not support modifications.
//[2] Alls cart form data such as pkey, ckey, quantity and product option and shipping information are taking default value, and no modification is supported at cart page
//@see ShopViewBehavior::getCartFormPreparedData($cartForm,$model);
//
//            $form = $this->beginWidget('CActiveForm', array(
//                'id'=>'cart-shortcut-form',
//                'action'=>url('cart/management/add'),
//                'enableAjaxValidation'=>false,
//              ));  
//              
//                $this->widget('zii.widgets.jui.CJuiButton',array(
//                            'name'=>'buy-button-'.hash('crc32b',$data->id),
//                            'buttonType'=>'button',
//                            'caption'=>Sii::t('sii','Add To Cart'),
//                            'value'=>'buybtn-'.hash('crc32b',$data->id),
//                            'options'=>array('disabled'=>!$data->hasInventory()?true:false),
//                            'htmlOptions'=>array('form'=>'cart-shortcut-form'),
//                            'onclick'=>'js:function(){addcart($(this).attr(\'form\'));}',
//                        ));
//
//            $this->endWidget();//end form widget
    ?>
    <?php /**
        echo CHtml::tag('span',array('class'=>'icon'.($data->commentCounter>0?' filled':'')),CHtml::link($data->commentCounter>0?'<i class="fa fa-comment"></i>':'<i class="fa fa-comment-o"></i>',$data->url.'#comments',array('title'=>Sii::t('sii','Write a comment'))));
        if ($data->commentCounter>0){
            echo CHtml::tag('span',array('class'=>'count'),$data->commentCounter);
        }
        $this->renderView('likes.buttonform',array('model'=>new LikeForm(get_class($data),$data->id),true));
        if ($data->likeCounter>0){
            echo CHtml::tag('span',array('class'=>'count'),$data->likeCounter);
        } */
    ?>
</div>
<div class="action-bar">
    <span>
    <?php   
        if ($controller->showLikeButton())
            $controller->renderView('likes.buttonform',array('model'=>new LikeForm(get_class($data),$data->id),true));

        if ($data->likeCounter>0)
            echo CHtml::tag('span',array('class'=>'like-'.strtolower(get_class($data)).'-total-'.$data->id.' count'),$data->likeCounter);
    ?>
    <span style="padding-left:3px;">
    <?php
        echo CHtml::tag('span',array('class'=>'icon'),CHtml::link('<i class="fa fa-comment-o"></i>',$controller->getCommentViewUrl($page,$data),['title'=>Sii::t('sii','Write a comment')]));

        if ($data->commentCounter>0)
            echo CHtml::tag('span',array('class'=>'comment-'.strtolower(get_class($data)).'-total-'.$data->id.' count'),$data->commentCounter);
     ?>
    </span>
</div>
