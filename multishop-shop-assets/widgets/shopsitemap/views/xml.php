<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <?php foreach($this->pageItems as $item): ?>
        <?php $this->render('_xml_url',['item'=>$item]);?>
    <?php endforeach; ?>

    <?php foreach($this->modelItems as $models): ?>
        <?php foreach($models as $item): ?>
            <?php $this->render('_xml_url',['item'=>$item]);?>
        <?php endforeach; ?>
    <?php endforeach; ?>

</urlset>
