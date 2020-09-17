<div id="product_upload" class="product-upload">
    <?php $this->getImportUploadForm();?>
    <span class="loading-gif" style="display:none;"><?php echo CHtml::image($this->getImage('loading',false));?></span>
</div>
<?php 
cs()->registerScript('fileuploaddestroy',"$('#ProductImportForm-form').bind('fileuploaddestroyed', function() {afterdestroyimportfile();});",CClientScript::POS_END);?>
