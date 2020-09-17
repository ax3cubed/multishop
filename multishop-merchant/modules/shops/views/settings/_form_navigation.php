<div class="subform">
    <div class="nav-candidates column">
        <p class="note">
            <?php echo Sii::t('sii','Drag and drop below menu items to the shaded area at right.');?>
            <?php echo Sii::t('sii','Each item can be either a main menu item or submenu item.');?>
        </p>
        <?php $this->widget('common.widgets.spagesection.SPageSection',['sections'=>$model->getNavCandidatesSectionData($this)]); ?>
    </div>
    <div id="<?php echo $model->mainMenuContainer;?>" class="nav-main-menu column">
        <?php echo CHtml::hiddenField('NavigationSettingsForm[mainMenu]',$model->getMainMenu()); ?>
        <p class="note">
            <?php echo Sii::t('sii','Move menu item up and down to sort the navigation order.');?>
            <?php echo Sii::t('sii','To discard menu item, drag and drop it back to left area.');?>
        </p>
        <?php $this->widget('zii.widgets.jui.CJuiSortable',[
                'id'=>'main_menu',
                'htmlOptions'=>['class'=>'connectedSortable main-menu'],
                'items'=>$model->mainMenuItems,
                'options'=>[
                    'delay'=>'300',
                    'connectWith'=>".connectedSortable",
                    'receive'=>new CJavaScriptExpression("function(event, ui){sortablereceivemainmenu(ui,$model->mainMenuLimit,'$model->mainMenuLimitMessage');}"),
                    'update'=>new CJavaScriptExpression("function(event, ui){updateheadermenusettings($('#$model->mainMenuContainer'));}"),
                    'remove'=>new CJavaScriptExpression("function(event, ui){updateheadermenusettings($('#$model->mainMenuContainer'));}"),
                ],
            ]);
        ?>
        <p class="note">
            <?php echo Sii::t('sii','Maximum {n} menu items allowed.',['{n}'=>$model->mainMenuLimit]);?>
        </p>
    </div>
</div>