<div class="community-search-bar">
    <?php 
        $this->renderPartial('common.modules.search.views.default._searchbar',[
            'placeholder'=>isset($placeholder)?$placeholder:'',
            'onsearch'=>$this->onsearch,
            'input'=>'community_q',
            'value'=>isset($value)?$value:'',
        ]);
    ?>
</div>
