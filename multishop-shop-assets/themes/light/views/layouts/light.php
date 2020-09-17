<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
?>   
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width = device-width, initial-scale = 1.0">
    <meta name="language" content="en">
    <meta name="description" content="<?php echo $this->metaDescription;?>">
    <meta name="keywords" content="<?php echo $this->metaKeywords;?>">
    <link rel="canonical" href="<?php echo request()->getHostInfo();?>">
    <title><?php echo CHtml::encode($this->pageTitle);?></title>
</head>

<body class="shop <?php echo Yii::app()->theme->name;?>">

    <?php echo $this->htmlBodyBegin; ?>
    
    <div class="page-container">
        <?php echo $content; ?>
        <?php $this->smodalWidget();?>
    </div>
    
    <div class="footer-container">
        <a href="#" class="scrollup"><i class="fa fa-arrow-circle-up"></i></a>        
        <?php echo $this->getLayoutFooter(); ?>
    </div>    

    <?php echo $this->htmlBodyEnd;?>

</body>
</html>