<url>
    <loc>
        <?php echo CHtml::encode($item['loc']); ?>
    </loc>
    <changefreq>
        <?php echo isset($item['frequency'])?$item['frequency']:'always';?>
    </changefreq>
    <priority>
        <?php echo isset($item['priority'])?$item['priority']:'0.5';?>
    </priority>
    <?php if (isset($item['lastmod'])):?>
        <lastmod>
            <?php echo $item['lastmod']?>
        </lastmod>
    <?php endif;?>
</url>