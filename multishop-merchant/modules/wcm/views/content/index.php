<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
?>
<?php $params = $this->getContentParams(); ?>
<div class="segment landing top">
    <?php echo $this->renderMarkdown('landing_overview',$params); ?>
</div>
<?php echo $this->renderMarkdown('landing_highlight1',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight2',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight3',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight4',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight5',$params); ?>
<?php
$this->renderPartial('_plan_signup');

//Carousel setup for landing_highlight3
// Not displaying Carousel Indicators and Controls
// As Indicators seems not working, and control click UX is not so good (page jumping and url contains #admin_laptop_carousel
Helper::registerJs('$("#admin_laptop_carousel").carousel();$("#admin_mobile_carousel").carousel();');
echo bootstrap()->Carousel([]);//call this to load bootstrap library
