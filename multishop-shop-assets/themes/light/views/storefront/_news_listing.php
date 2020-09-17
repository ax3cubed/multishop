<?php 
$this->widget('common.widgets.SListView', [
    'id'=>'news_list',
    'dataProvider'=>$dataProvider,
    'template'=>'{summary}{items}{pager}',
    'itemView'=>$this->getThemeView('_news'),
]);