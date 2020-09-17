<div class="lower_footer">
    <div class="corporate">
        <span><a href="<?php echo url('about');?>"><?php echo Sii::t('sii','About');?></a></span>
        <?php if (Config::getSystemSetting('email_notification')==Config::ON):?>
            <span><a href="<?php echo url('contact');?>"><?php echo Sii::t('sii','Contact');?></a></span>
        <?php endif;?>
        <span><a href="<?php echo url('terms');?>"><?php echo Sii::t('sii','Terms of Service');?></a></span>
        <span><a href="<?php echo url('privacy');?>"><?php echo Sii::t('sii','Privacy Policy');?></a></span>
    </div>
    <div class="copyright">
        <?php echo Sii::t('sii','Copyright &copy; 2015 - {year} {company}.',array('{year}'=>date('Y'),'{company}'=>param('ORG_NAME')));?>     
    </div>
</div>

<?php $this->renderPartial('common.views.version.index'); ?>

<?php  
cs()->registerScript('footer','$(\'#language\').change(function (){$(\'#langform\').submit();});');
    
