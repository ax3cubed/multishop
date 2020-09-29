<?php cs()->registerScript('chosen-process-items','$(\'.chzn-select-process-items\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-po-random','$(\'.chzn-select-po-random\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-so-random','$(\'.chzn-select-so-random\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-po-separator','$(\'.chzn-select-po-separator\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-so-separator','$(\'.chzn-select-so-separator\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php $this->widget('common.widgets.spage.SPage',array(
        'id'=>'multisections_settings',
        'heading'=> false,
        'linebreak'=>false,    
        'loader'=>false,    
        'layout'=>false,    
        'sections'=>$model->getSectionsData(),
    ));