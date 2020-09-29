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
    <meta name="description" content="<?php echo $this->metaDescription;?>">
    <meta name="keywords" content="<?php echo $this->metaKeywords;?>">
    <link rel="canonical" href="<?php echo $this->canonicalUrl;?>">
    <title><?php echo CHtml::encode($this->pageTitle);?></title>
</head>

<body class="<?php echo isset($this->htmlBodyCssClass)?$this->htmlBodyCssClass:'';?>">

    <?php 
        $this->widget('common.widgets.soffcanvasmenu.SOffCanvasMenu',[
            'menus'=>[
                [
                    'id'=>'offcanvas_community_menu',
                    'heading'=>app()->ctrlManager->getSiteLogo(true),//always white font logo
                    'content'=>$this->widget('community.components.CommunityMenu',[
                            'user'=>user(),
                            'offCanvas'=>true,
                        ],true),
                ],
            ],
            'autoMenuOpeners'=>false,//open element is handled at header-container
            'pageContent'=>$this->renderPartial('community.views.layouts._community_body',['content'=>$content],true),
        ]);
    ?>

</body>
</html>