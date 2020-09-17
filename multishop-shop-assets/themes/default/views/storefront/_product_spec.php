<div style="padding: 10px 10px;overflow-x:auto">
    <?php 
        if ($details==null) 
            echo '<i>'.Sii::t('sii','This product has no specification.').'</i>';
        else
            echo $details;
    ?>
</div>