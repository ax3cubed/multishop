<?php
$tagWrapper = CHtml::openTag('div',array('class'=>'tag-wrapper'));
$tagWrapper .= CHtml::tag('span',array('class'=>'name'),$data['tag']);
$tagWrapper .= CHtml::tag('span',array('class'=>'count'),' ('.$data['count'].')');
$tagWrapper .= CHtml::closeTag('div');
echo CHtml::link($tagWrapper,$data['url']);
