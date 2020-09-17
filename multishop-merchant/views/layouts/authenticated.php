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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width = device-width, initial-scale = 1.0">
    <meta name="language" content="en" />
    <title><?php echo CHtml::encode($this->pageTitle);?></title>
</head>

<body class="<?php echo isset($this->htmlBodyCssClass)?$this->htmlBodyCssClass:'';?> login">

    <?php 
        $this->widget('common.widgets.soffcanvasmenu.SOffCanvasMenu',[
            'menus'=>Yii::app()->ctrlManager->getOffCanvasMenu($this),
            'autoMenuOpeners'=>false,//open element is handled at header-container
            'pageContent'=>$this->renderPartial('merchant.views.layouts._authenticated_body',['content'=>$content],true),
        ]);
    ?>

</body>
</html>