<h1><?php echo Sii::t('sii','News');?></h1>
<?php
$this->renderPartial($this->getThemeView('_news'),['data'=>$model]);