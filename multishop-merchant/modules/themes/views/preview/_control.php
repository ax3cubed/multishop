<div class="preview-control">
    
    <h1><?php echo $theme;?></h1>
    
    <?php if (isset($style)):?>
        <h2><?php echo $style;?></h2>
    <?php endif;?>
        
    <ul>
        <li class="desktop active">
            <a href="javascript:void(0);" onclick="switchdevice('desktop');">
                <i class="fa fa-desktop"></i>
                <span>
                    <?php echo Sii::t('sii','Desktop');?>
                </span>
            </a>
        </li>
        <li class="tablet">
            <a href="javascript:void(0);" onclick="switchdevice('tablet');">
                <i class="fa fa-tablet"></i>
                <span>
                    <?php echo Sii::t('sii','Tablet');?>
                </span>
            </a>
        </li>
        <li class="mobile">
            <a href="javascript:void(0);" onclick="switchdevice('mobile');">
                <i class="fa fa-mobile"></i>
                <span>
                    <?php echo Sii::t('sii','Mobile');?>
                </span>
            </a>
        </li>
    </ul>
</div>
