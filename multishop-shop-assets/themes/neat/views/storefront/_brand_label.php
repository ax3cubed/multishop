<?php if ($page->shopModel->hasImage()):?>

    <div class="company-logo">
        <?php if ($this->onFacebook())
                  echo $page->shopModel->logo;
              else 
                  echo l($page->shopModel->logo,$page->homeUrl);  
        ?>
    </div>            

<?php else:?>

    <div class="company-name">
        <?php if ($this->onFacebook())
                  echo $page->shopModel->parseName(user()->getLocale());
              else 
                  echo l($page->shopModel->parseName(user()->getLocale()),$model->url);    
        ?>
        <div class="tagline">
            <?php echo $page->shopModel->displayLanguageValue('tagline',user()->getLocale()); ?>
        </div>
    </div>    

<?php endif;?>
