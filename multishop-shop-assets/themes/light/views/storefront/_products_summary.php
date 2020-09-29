<h2>
    <?php echo $page->getDisplayName(user()->getLocale());?>
</h2>
<span class="total">
    <?php echo $dataProvider->getTotalItemCount();?>
</span>
<br>
<span class="sort-by">
    <span class="label">
        <?php echo t('Sort By');?> 
    </span>
    <span class="label-icon">
        <i class="fa fa-sort"></i>
    </span>
    <?php echo $page->getSortBySelect();?>
</span>
