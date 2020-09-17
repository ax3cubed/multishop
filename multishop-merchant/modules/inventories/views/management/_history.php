 <?php 
$this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>'inventory_history_grid',
    'dataProvider'=>$model->searchHistories(),
    'columns'=>array(
        array(
           'name'=>'type',
           'value'=>'$data->getTypeDesc()',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
        ),
        array(
           'name'=>'movement',
           'htmlOptions'=>array('style'=>'text-align:center;width:5%'),
           'type'=>'html',
        ),
        array(
           'name'=>'description',
           'htmlOptions'=>array('style'=>'text-align:center'),
           'type'=>'html',
        ),
        array(
           'name'=>'post_available',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
        ),
        array(
           'name'=>'post_quantity',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
        ),
        array(
           'name'=>'create_time',
           'value'=>'$data->inventory->formatDatetime($data->create_time,true)',
           'htmlOptions'=>array('style'=>'text-align:center;width:20%'),
           'type'=>'html',
        ),
    ),
)); 

//reset pagination url
SPageSection::loadPaginationUrlScript('inventories/management/history', $model->id, 'inventory_history_grid', 'InventoryHistory');

