<div id="<?php echo $this->id;?>" class="shop-faq">
<?php  echo bootstrap()->Collapse([
            'items' => $this->getFAQItems(),
            'encodeLabels'=>false,
        ]);
?>
</div>

