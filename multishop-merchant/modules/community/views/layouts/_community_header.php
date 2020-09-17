<span class="sitelogo">
    <?php echo app()->ctrlManager->getSiteLogo('black'); //always black font logo ?>
</span>
<span class="communitylogo">
    <?php echo l(Sii::t('sii','Community'),url('community')); ?>
</span>
<?php 
$this->widget('community.components.CommunityMenu',[
    'user'=>user(),
    'cssClass'=>'nav-menu',
    'offCanvas'=>false,
]);