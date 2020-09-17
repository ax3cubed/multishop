<div class="segment get-plan">
    <div class="get-plan-text">
        <h2><?php echo Sii::t('sii','{app} is your best partner for your eCommerce business',['{app}'=>param('ORG_NAME')]);?></h2>
    </div>
    <?php echo bootstrap()->button([
            'label' => Sii::t('sii','Launch My Shop'),
            'options' => [
                'class' => 'form-input get-plan ui-button',
                'onclick'=>'window.location.href="'.Yii::app()->urlManager->createHostUrl('signup').'"',
            ],
        ]);
    ?>
</div>

