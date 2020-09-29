<div class="news-image">
    <?php echo CHtml::image($model->imageOriginalUrl);?>
</div>
<div class="content">
    <?php $model->languageForm->renderForm($this,Helper::READONLY);?>
</div>
<div class="lastedit">
   <?php echo Sii::t('sii','{shop}, last update at {datetime}',array('{shop}'=>$model->shop->displayLanguageValue('name',user()->getLocale()),'{datetime}'=>$model->formatDatetime($model->update_time,true)));?>
</div>   
