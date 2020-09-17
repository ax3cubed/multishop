<div class="answer rounded3" style="margin-left: 0px">
    <span class="character"><?php echo Sii::t('sii','A: ');?></span>
    <?php echo Helper::purify($model->answer); ?>
    <span class="time">
        <?php echo $model->formatDatetime($model->answer_time,true);?> 
    </span>
</div>
