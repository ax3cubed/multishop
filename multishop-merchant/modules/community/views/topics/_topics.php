<?php
$this->widget($this->getModule()->getClass('listview'), [
                'dataProvider'=>$dataProvider,
                'template'=>'{items}',//no {summary} as pagination not handled; Limit to 100 tags for each group, max is 300 tags
                //'template'=>'{summary}{items}{pager}',
                'itemView'=>$this->module->getView('topic'),
            ]);