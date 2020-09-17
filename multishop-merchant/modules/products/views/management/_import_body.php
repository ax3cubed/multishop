<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('upload','guide','template'),
    'columns'=>array(
        array(
            'content-column'=>$this->renderPartial('_import_upload',array(),true),
        ),
        array(
            array('label'=>SButtonColumn::getButtonIcon('download'),'type'=>'raw','value'=>ProductImportManager::getImportGuideLink()),
            array('label'=>SButtonColumn::getButtonIcon('download'),'type'=>'raw','value'=>ProductImportManager::getImportTemplateLink()),
        ),
    ),
));
