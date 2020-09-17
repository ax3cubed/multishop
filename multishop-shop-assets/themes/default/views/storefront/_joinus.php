<?php if ($this->showJoinUsButton()): ?>
    <div class="join-us-bar">
         <?php $this->widget('zii.widgets.jui.CJuiButton',[
                'name'=>'joinusbtn-'.$page->name,
                'buttonType'=>'button',
                'caption'=>Sii::t('sii','Register'),
                'value'=>'joinusbtn',
                'onclick'=>'js:function(){'.$this->loadSignupScript($page).'}',
                'htmlOptions'=>['class'=>'joinus ui-button'],
            ]);
         ?>
         <span class="text"><?php echo Sii::t('sii','to view more');?></span>
    </div>
<?php endif; ?>
