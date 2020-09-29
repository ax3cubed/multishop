<?php 
    $this->widget('common.widgets.SListView', [
        'id' => $topic,
        'dataProvider' => $dataProvider,
        'htmlOptions' => ['class'=>'catalog-container','data-total'=>$dataProvider->getTotalItemCount()],
        'itemView' => $this->getThemeView('_product'),
        'viewData' => [
            'page'=>$page,
            'theme'=>$theme,
        ],
        'template' => $this->showJoinUsButton()?'{items}':'{items}{pager}',
        'infiniteScroll'=>[
            'rowSelector'=>'.product', 
            'listViewId'=>$topic, 
            'onRenderComplete'=>'hover(\'.product\');',
        ],
    ]);
    cs()->registerScript('hover-product','hover(\'.product\');',CClientScript::POS_END);