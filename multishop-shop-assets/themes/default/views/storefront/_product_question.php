<div class="questions">
   <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'template'=>'{items}',
            'emptyText'=>'',
            'viewData'=>array('showAvatar'=>true,'htmlDecode'=>true),
            'itemView'=>$this->module->getView('questionview'),
    )); ?>
</div>
<div class="<?php echo $questionForm->id;?> postquestion">
     <?php $this->renderview('questionform',array('model'=>$questionForm,'preview'=>$this->onPreview()?true:null));?>
</div>