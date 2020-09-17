<div class="segment" id="community_page">
    <?php
        $this->calloutWidget([
            'icon'=>'<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-file-text-o fa-stack-1x fa-inverse"></i></span>',
            'heading'=>CHtml::link(Sii::t('sii','Read Tutorials'),url('community/tutorials')),
            'description'=>Sii::t('sii','Learn from curated tutorials'),
            'cssClass'=>'tutorials',
        ]);
        
        $this->calloutWidget([
            'icon'=>'<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-files-o fa-stack-1x fa-inverse"></i></span>',
            'heading'=>CHtml::link(Sii::t('sii','Master Tutorial Series'),url('community/tutorials/series')),
            'description'=>Sii::t('sii','Fast track learning'),
            'cssClass'=>'tutorial-series',
        ]);
    
        $this->calloutWidget([
            'icon'=>'<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-question fa-stack-1x fa-inverse"></i></span>',
            'heading'=>CHtml::link(Sii::t('sii','Ask Questions'),url('community/questions')),
            'description'=>Sii::t('sii','Help each other by Q&A'),
            'cssClass'=>'questions',
        ]);
    
        $this->calloutWidget([
            'icon'=>'<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-tags fa-stack-1x fa-inverse"></i></span>',
            'heading'=>CHtml::link(Sii::t('sii','Search Topics'),url('community/topics')),
            'description'=>Sii::t('sii','A wide range of topics'),
            'cssClass'=>'topics',
        ]);

        $this->renderPartial($this->module->getView('search'),['placeholder'=>Sii::t('sii','Search Community')]);

    ?>
</div>
<?php      
$this->widget('CTabView', ['tabs'=>$this->getPages(),'cssFile'=>false,'id'=>'community_tabs','htmlOptions'=>['class'=>'cTab']]);

$this->smodalWidget();

