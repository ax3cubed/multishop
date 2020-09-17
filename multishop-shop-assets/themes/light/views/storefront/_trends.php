<?php
//This layout can be achieved the same effect via trends_layout.json file.
?>
<h2><?php echo Sii::t('sii','Trends');?></h2>
    
<div class="trends-menu">
    
    <?php 
        $this->widget('zii.widgets.CMenu', [
            'encodeLabel'=>false,                            
            'items'=>$page->trendsMenu,
        ]);
    ?>
</div>

<div class="trends-container">
    <?php $this->renderPartial($this->getThemeView('_trends_listing'),[
                'topic'=>$topic,
                'page'=>$page,
                'dataProvider'=>$dataProvider,
            ]);
    ?>      
</div>

<?php 
    echo $this->renderPartial($this->getThemeView('_joinus'),['idx'=>'trend']);
