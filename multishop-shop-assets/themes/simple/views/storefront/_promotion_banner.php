<?php 
$this->renderPartial($this->getThemeView('_bga_banner'),[
    'model'=>$data,
    'onclick'=>$this->loadCampaignViewScript($data,$page),
]);
