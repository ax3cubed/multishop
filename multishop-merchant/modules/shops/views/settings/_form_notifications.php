<?php cs()->registerScript('chosen-inventory','$(\'.chzn-select-inventory\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php 
$this->widget('common.widgets.spage.SPage',array(
        'id'=>'multisections_settings',
        'heading'=> false,
        'linebreak'=>false,    
        'loader'=>false,    
        'layout'=>false,    
        'sections'=>$model->getSectionsData(),
    ));
