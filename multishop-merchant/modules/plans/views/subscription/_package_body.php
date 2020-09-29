<?php
$mainRate = $this->getMainRate($data->plans);
$monthlyRate = $this->getMonthlyRate($data->plans);
$planCardWidth = ($total < 4) ? round(100 / ($total + 1)) : 24;//max is 4 cards per row,keep to 24% each
?>
<div class="plan-package <?php echo 'width-p-'.$planCardWidth; ?>">
    
    <div class="plan-card rounded">
        
        <h1>
            <?php echo Package::siiName($data->id); ?>
        </h1>

        <?php if ($data->showPricing):?>
            <div class="plan-price">
                <span class="currency"><?= $mainRate->currency;?></span><?= $mainRate->dollar;?>
                <?php if ($mainRate->dollar>0):?>
                    <span class="cents"><?= $mainRate->cents;?></span>
                <?php endif;?>
            </div>

            <div class="plan-price-details">
                <?php echo $mainRate->details;?>
            </div>

            <?php if (isset($monthlyRate)):?>
                <div class="plan-price-details others">
                    <?php echo Sii::t('sii','{currency} {price} billed monthly',['{currency}'=>$monthlyRate->currency,'{price}'=>$monthlyRate->price]);?>
                </div>
            <?php endif;?>
        
        <?php endif;?>        

        <?php if ($data->showButton):?>
        <div class="plan-button">
            <?php $this->renderPackageButton($data,$mainRate->dollar,$shop); ?>
        </div>
        <?php endif;?>        
        
    </div>

    <div class="plan-features">
        <?php $this->renderFeatureSummary($data); ?>
    </div>
    
</div>