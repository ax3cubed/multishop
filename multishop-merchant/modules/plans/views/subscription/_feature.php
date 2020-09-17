<?php $feature = Feature::parseKey($data['name'],'name');?>
<li class="feature-item">
    <?php echo Feature::getNameDesc($feature);?>
</li>