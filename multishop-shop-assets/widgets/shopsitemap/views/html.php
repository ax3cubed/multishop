<ul>
    <h2><?php echo Sii::t('sii','Pages');?></h2>
    <?php foreach($this->pageItems as $page): ?>
        <li>
            <?php echo CHtml::link(CHtml::encode($page['name']),$page['loc']); ?>
        </li>
    <?php endforeach; ?>

</ul>
<?php foreach($this->modelItems as $group => $models): ?>
    <ul>
        <h2><?php echo $group;?></h2>
        <?php foreach($models as $model): ?>
        <li>
            <?php echo CHtml::link(CHtml::encode($model['name']),$model['loc']); ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>