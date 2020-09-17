<h1><?php echo Sii::t('sii','{plan} includes:',['{plan}'=>Sii::t('sii',$package->name)]);?></h1>
<h2>&nbsp;</h2>
<?php 
    if ($package['type']!=Plan::TRIAL){
        //get a plan representative for presenting plan feaures
        $this->widget('common.widgets.SListView', [
            'dataProvider'=>$this->getPlanDataProvider($package->plans),
            'template'=>'{items}',
            'itemView'=>'plans.views.subscription._feature_list',
            'htmlOptions'=>['class'=>'plan-feature'],
        ]);
    }