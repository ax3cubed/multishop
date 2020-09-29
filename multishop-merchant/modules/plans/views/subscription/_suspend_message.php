<div class="suspend-message">

    <p><?php echo Sii::t('sii','You have failed to make payment for your subscription within {days} days when your subscription became past due or before the due date {dunning_date}.',['{days}'=>Config::getSystemSetting('subscription_dunning_days'),'{dunning_date}'=>$model->dunningDate]);?></p>

    <p><?php echo Sii::t('sii','As a result, your shop is automatically suspended and you are not be able to use your shop anymore. If you wish to resume your shop, please write to us at {email}.',['{email}'=>Config::getSystemSetting('email_contact')]);?></p>

    <p><?php echo Sii::t('sii','Or, click {link} to contact us.',array('{link}'=>CHtml::link(Sii::t('sii','here'),url('/contact'))));?></p>

</div>