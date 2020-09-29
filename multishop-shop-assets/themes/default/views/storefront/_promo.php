<?php $this->widget('common.widgets.SListView', [
            'id'=>'promotion_list',
            'dataProvider'=>$dataProvider,
            'template'=>'{items}{pager}',
            'itemView'=>$this->getThemeView('_promo_banner'),
            'infiniteScroll'=>[
                'rowSelector'=>'.promo-product', 
                'listViewId'=>$idx, 
                'onRenderComplete'=>'hover(\'.promo-product\');',
            ],
        ]);