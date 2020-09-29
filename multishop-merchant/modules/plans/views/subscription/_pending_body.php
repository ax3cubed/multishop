<div class="row">
    <ul>
        <li><?php echo Sii::t('sii','You have subscribed to <strong>{package}</strong>.',array('{package}'=>$model->name));?></li>
        <li><?php echo Sii::t('sii','Subscription No is <strong>{no}</strong>.',array('{no}'=>$model->subscription_no));?></li>
        <li><?php echo Sii::t('sii','Subscription start date is <strong>{date}</strong>.',array('{date}'=>$model->start_date));?></li>
        <li><?php echo Sii::t('sii','You will be paying <strong>{price}</strong>.',array('{price}'=>$model->plan->priceDesc));?></li>
    </ul>
</div>