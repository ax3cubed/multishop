<?php
$this->widget($this->getModule()->getClass('listview'), [
        'ajaxUpdate'=>false,//If it is set false, it means sorting and pagination will be performed in normal page requests instead of AJAX requests
        'dataProvider'=>$dataProvider,
        'template'=>'{summary}{items}{pager}',
        'itemView'=>$this->module->getView('questionlist'),
    ]);