<div class="related-tutorial-series">
    <div class="series-name">
        <?php echo Sii::t('sii','Related to {series}, see also:',['{series}'=>CHtml::link($data->localeName(user()->getLocale()),TutorialSeries::getPublicUrl($data->slug))]); ?>
    </div>
    <div class="tutorials">
        <?php
            $this->widget('common.widgets.SListView', array(
                'id' => 'tutorial_list',
                'dataProvider' => TutorialSeries::model()->findByPk($data->id)->searchTutorials(user()->getLocale()),
                'itemView' => 'common.modules.tutorials.views.series._tutorial',
                'viewData'=>['showStatus'=>false,'currentTutorial'=>$currentTutorial],
                'template' => '{items}',
                ));        
        ?>
    </div>
</div>

