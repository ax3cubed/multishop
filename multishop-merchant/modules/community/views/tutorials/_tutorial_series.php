<?php $this->widget('zii.widgets.CListView', [
    'dataProvider'=>$dataProvider,
    'template'=>'{items}',
    'emptyText'=>'',
    'viewData'=>['currentTutorial'=>$currentTutorial],
    'itemView'=>'_tutorial_series_view',
]); 