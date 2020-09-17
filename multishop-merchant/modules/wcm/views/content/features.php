<div class="segment features top">
    <h1>
        <?php echo WcmFeature::title($page);?>
    </h1>
    <div class="features-summary">
        <ul>
            <?php 
                foreach (WcmFeature::items($page) as $item) {
                    echo CHtml::tag('li',[],'<i class="fa fa-check-circle fa-fw"></i>'.$item);
                } 
            ?>
        </ul>
    </div>   
    <?php
//        if ($page=='features_highlights'){
//            $this->widget('zii.widgets.jui.CJuiButton',[
//                'name'=>'signup-button',
//                'buttonType'=>'button',
//                'caption'=>Sii::t('sii','Get started for free'),
//                'value'=>'btn',
//                'htmlOptions'=>['class'=>'form-input features primary','onclick'=>'window.location.href="'.url('account/signup/merchant').'"'],
//            ]); 
//        }
//
        if ($page=='features_website' /*|| $page=='features_highlights'*/){
            $this->widget('zii.widgets.jui.CJuiButton',[
                'name'=>'signup-button',
                'buttonType'=>'button',
                'caption'=>Sii::t('sii','View Demo Shop'),
                'value'=>'btn',
                'htmlOptions'=>['class'=>'form-input features','target'=>'_blank','onclick'=>'window. open("'.Config::getSystemSetting('shop_demo_link').'");'],
            ]); 
        }
        if ($page=='features_chatbots' /*|| $page=='features_highlights'*/){
            $this->widget('zii.widgets.jui.CJuiButton',[
                'name'=>'signup-button',
                'buttonType'=>'button',
                'caption'=>Sii::t('sii','View Demo Chatbot'),
                'value'=>'btn',
                'htmlOptions'=>['class'=>'form-input features','target'=>'_blank','onclick'=>'window. open("'.Config::getSystemSetting('chatbot_demo_link').'");'],
            ]); 
        }
    ?>
    <?php //echo $this->renderMarkdown('features_overview',['SITE_NAME','CDN_BASE_URL'=>app()->urlManager->createCdnUrl()]); ?>
<!--    <div class="buttons">-->
        <?php 
//            $this->widget('zii.widgets.jui.CJuiButton',[
//                'name'=>'signup-button',
//                'buttonType'=>'button',
//                'caption'=>Sii::t('sii','Learn More'),
//                'value'=>'btn',
//                'htmlOptions'=>['class'=>'form-input pricing','onclick'=>'window.location.href="'.Yii::app()->urlManager->createHostUrl('features/chatbots#conversational-commerce').'"'],
//            ]); 
//        
//            $this->widget('zii.widgets.jui.CJuiButton',[
//                'name'=>'signup-button',
//                'buttonType'=>'button',
//                'caption'=>Sii::t('sii','Launch My Shop'),
//                'value'=>'btn',
//                'htmlOptions'=>['class'=>'form-input pricing','onclick'=>'window.location.href="'.Yii::app()->urlManager->createHostUrl('signup').'"'],
//            ]); 
//        
//            $this->widget('zii.widgets.jui.CJuiButton',[
//                'name'=>'signup-button',
//                'buttonType'=>'button',
//                'caption'=>Sii::t('sii','More Features'),
//                'value'=>'btn',
//                'htmlOptions'=>['class'=>'form-input pricing','onclick'=>'window.location.href="'.Yii::app()->urlManager->createHostUrl('features/highlights#conversational-commerce').'"'],
//            ]); 
        ?>
<!--    </div>-->
</div>
<div class="segment features-set">
<!--    <div class="column menu">
        <?php //$this->featureMenuWidget($page); ?>
    </div>-->
<!--    <div class="column menu-content">-->
    <div class="feature-content">
        <?php 
            if ($page!='features_highlights'){
                $this->widget('zii.widgets.CBreadcrumbs', [
                    'htmlOptions'=>['id'=>'all','class'=>'breadcrumbs'],
                    'links'=>[Sii::t('sii','Features')=>url('/features'),WcmFeature::title($page)],
                    'homeLink'=>CHtml::link('<i class="fa fa-home"></i>', url('/')),
                    'separator'=>'<i class="fa fa-angle-right"></i>',
                ]);
            }
        ?>
        <?php echo $this->renderMarkdown($page,$this->getContentParams()); ?>
    </div>
    <p class="clearfix"></p>
</div>
<?php
$this->renderPartial('_plan_signup');