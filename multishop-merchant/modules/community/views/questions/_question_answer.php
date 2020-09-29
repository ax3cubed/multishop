<div class="<?php echo $commentForm->id;?> comments">
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'template'=>'{items}',
            'emptyText'=>'',
            'itemView'=>$this->getModule()->getView('commentview'),
    )); ?>
</div>
<div class="<?php echo $commentForm->id;?> postcomment">
     <?php $this->renderview('commentform',array('model'=>$commentForm,'placeholder'=>Sii::t('sii','Write your answer for this question')));?>
</div>
