<div class="preview-container">
    
    <?php $this->renderPartial('_control',['theme'=>$theme,'style'=>$style]);?>
    
    <div class="preview-panel">
        
        <?php 
            $this->pageTitle = Sii::t('sii','{theme} Preview',['{theme}'=>$this->theme->localeName(user()->getLocale())]).' | '.$this->page->owner->localeName(user()->getLocale());
        
            if ($this->showFrame)
                $this->renderPartial('_frame',['theme'=>$theme,'style'=>$style]);
            else
                echo CHtml::tag('p',['class'=>'message'],$this->message);
        ?>
        
    </div>
    
</div>