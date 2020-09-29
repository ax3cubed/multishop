<?php
//Required for now by StorefrontController::actionCatalog
$this->widget('common.widgets.SListView', [
    'id' => $idx,
    'dataProvider' => $dataProvider,
    'htmlOptions' => ['class'=>'catalog-container','data-total'=>$dataProvider->getTotalItemCount()],
    'itemView' => $this->getThemeView('_product'),
    'viewData' => [
        'page'=>$page,
        'theme'=>$page->pageTheme,
    ],
    'template' => $this->showJoinUsButton()?'{items}':'{items}{pager}',
]);
