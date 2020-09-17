<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.models.ShopTheme');
Yii::import('common.modules.shops.components.ShopNavigation');//required by navMenu, userMenu
/**
 * This trait containts all the support in-built shop widgets can be used in layout
 * 
 * @see ThemeGridLayout use together with this
 * @author kwlok
 */
trait ShopWidgets
{
    public static $listItemPromotion = 'promotion';
    public static $listItemProduct   = 'product';
    public static $listItemTrend     = 'trend';
    /**
     * List of shop widgets
     */
    private $_dpList = [];//data providers list
    private $_liList = [];//list item list
    private $_mList = [];//menu list
    /**
     * Create a new shop page data
     * @param type $page Indicate which page data provider to call; If null, it will follow current page id
     * @param array $filter @see ShopPageBehavior::setPageSettings() for input format
     * @return type
     */
    public function createPage($page,$filter=[])
    {
        $pageObj = new ShopPage($page, $this->page->shopModel, $this->controller, false);//do not track visit (else double or multiple counts)
        if (!empty($filter)){
            if ($page==ShopPage::TRENDS){
                $pageObj->trendTopic = $filter['topic'];//trends subpage
            }
            else {
                $pageObj->setFilter($filter);
            }
        }
        return $pageObj;
    }      
    /**
     * @return string breadcrumbs
     */
    public function getBreadcrumbs()
    {
        if ($this->page->hasFilter){
            $links = [ShopPage::getTitle(ShopPage::PRODUCTS)=>$this->page->getUrl(ShopPage::PRODUCTS, [], request()->isSecureConnection)];//add 'Products' label as 1st level menu
            $sublinks = $this->page->filter->getMenuArray($this->locale);
            //logTrace(__METHOD__.' sublinks',$sublinks);
            foreach ($sublinks as $name => $url) {
                $sublinks[$name] = $this->page->appendExtraQueryParams($url);
            }
            $links = array_merge($links,$sublinks);    
        }
        elseif ($this->page->model instanceof Product){
            $links = [ShopPage::getTitle(ShopPage::PRODUCTS)=>$this->page->getUrl(ShopPage::PRODUCTS, [], request()->isSecureConnection)];//add 'Products' label as 1st level menu
            $links = array_merge($links,[$this->pageModelName]);    
        }
        elseif ($this->page->model instanceof Campaign){
            $links = [ShopPage::getTitle(ShopPage::PROMOTIONS)=>$this->page->getUrl(ShopPage::PROMOTIONS, [], request()->isSecureConnection)];//add 'Promotions' label as 1st level menu
            $links = array_merge($links,[$this->pageModelName]);    
        }
        elseif ($this->page->model instanceof Page){
            $links = [$this->pageModelName];    
        }
        else
            $links = [ShopPage::getTitle($this->page->id)];    
            
        return $this->renderTheme('_breadcrumbs', ['homeUrl'=>$this->page->getHomeUrl(request()->isSecureConnection),'links'=>$links]);
    }
    /**
     * @return string category menu (vertical form)
     */
    public function getCategoryMenu()
    {
        return $this->widget('shopwidgets.shopproductmenu.ProductCategoryMenu',[
            'page'=>$this->page,
            'locale'=>$this->locale,
            'useJavascript'=>false,
            'activeMenuItem'=>$this->page->hasFilter?$this->page->filter->value:null,
        ],true);
    }
    /**
     * @return string brand menu (vertical form)
     */
    public function getBrandMenu()
    {
        return $this->widget('shopwidgets.shopproductmenu.ProductBrandMenu',[
            'page'=>$this->page,
            'locale'=>$this->locale,
            'useJavascript'=>false,
            'activeMenuItem'=>$this->page->hasFilter?$this->page->filter->value:null,
        ],true);
    }       
    /**
     * @return string news panel (vertical listing)
     */
    public function getNewsPanel()
    {
        return $this->widget('shopwidgets.shopnewspanel.ShopNewsPanel',[
            'page'=>$this->page,
            'locale'=>$this->locale,
        ],true);
    }    
    /**
     * @return string bga promotions (vertical listing)
     */
    public function getBgaPromotions()
    {
        return $this->widget('shopwidgets.shoppromotions.ShopPromotions',[
            'page'=>$this->page,
        ],true);
    }     
    
    public function getAnimatedSearch()
    {
        return $this->widget('common.widgets.sanimatedsearch.SAnimatedSearch',[
            'placeholder'=>Sii::t('sii','Search'),
            'url'=>$this->page->getUrl(ShopPage::SEARCH),
            'useImage'=>false,
        ],true);      
    }
    
    public function getSearch()
    {
        return $this->widget('common.widgets.ssearch.SSearch',[
            'placeholder'=>Sii::t('sii','Search'),
            'url'=> $this->page->edit ? false : $this->page->getUrl(ShopPage::SEARCH),
        ],true);      
    }    
    /**
     * Get messenger "Message Us" plugin
     * @return type
     */
    public function getMessageUs($welcomeView=true,$container=true)
    {
        return $this->widget('shopwidgets.shopmessenger.ShopMessenger',[
            'shopModel'=>$this->page->shopModel,
            'welcomeView'=>$welcomeView,
            'container'=>$container,
        ],true);
    }
    /**
     * Get messenger "Message Us" button
     * @return type
     */
    public function getMessageUsButton()
    {
        return $this->getMessageUs(false,false);
    }    
    /**
     * Get Send To Messenger plugin
     * @param type $dataRef The pass through param to Messenger
     * @return type
     */
    public function getSendToMessenger()
    {
        return $this->widget('shopwidgets.shopmessenger.ShopSendToMessenger',[
            'shopModel'=>$this->page->shopModel,
        ],true);
    }        
    /**
     * Trends menu widget
     * @param type $containerSelection The css selection to overwrite trends result
     * @param type $view The view file to render
     * @return type
     */
    public function getTrendsMenu($containerSelection='.sgridlistblock.trends-container',$view=null)
    {
        return $this->widget('zii.widgets.CMenu', [
                'encodeLabel'=>false,                            
                'items'=>$this->page->getTrendsMenu($containerSelection,$view),
            ],true);
    }
    /**
     * @todo This should be made as a widget and can be added 'adhocly' in page editor
     * @return type
     */
    public function getFacebookShareButton()
    {
        return CHtml::tag('div',['class'=>'facebook-share-button'],$this->widget('FacebookShareButton',['url'=>$this->page->model->getUrlForSocialMedia()],true));
    }
    /**
     * @todo This widget is temporary not display (feature hidden) as it seems more applicable as market place feature, and not for invidivual brand shop
     * @return type
     */
    public function getLikesPortlet()
    {
        return $this->renderTheme('_likes_portlet', ['likeForm'=>$this->page->getLikeForm()]);
    }    

    public function getDataProvidersList()
    {
        if (empty($this->_dpList)){
            $this->_dpList = [0=>Sii::t('sii','Select Category')];
            //Add news data provider list
            $this->_dpList = array_merge($this->_dpList,[
                ShopPage::NEWS => Sii::t('sii','Latest News'),
            ]);
            //Add promotions data provider list
            $this->_dpList = array_merge($this->_dpList,[
                ShopPage::PROMOTIONS => Sii::t('sii','Latest Promotions'),
            ]);
            //Add category data provider list
            $categoryMenu = new ShopBrowseMenu(ShopBrowseMenu::CATEGORY, $this->page->shopModel, $this->controller);
            $categoryMenu->currentPage = $this->page;
            foreach ($categoryMenu->getMenu($this->locale) as $key => $value) {
                $this->_dpList[ShopPage::CATEGORY.':'.$key] = Sii::t('sii','Category - ').$value;
            }
            //Add brand data provider list
            $brandMenu = new ShopBrowseMenu(ShopBrowseMenu::BRAND, $this->page->shopModel, $this->controller);
            $brandMenu->currentPage = $this->page;
            foreach ($brandMenu->getMenu($this->locale) as $key => $value) {
                $this->_dpList[ShopPage::BRAND.':'.$key] = Sii::t('sii','Brands - ').$value;
            }
            //Add trends data provider list
            $this->_dpList = array_merge($this->_dpList,[
                ShopPage::TRENDS.':'.ShopPage::TREND_MOSTPURCHASED => Sii::t('sii','Trends - ').Sii::t('sii','Most Purchased Products'),
                ShopPage::TRENDS.':'.ShopPage::TREND_MOSTLIKED => Sii::t('sii','Trends - ').Sii::t('sii','Most Likes Products'),
                ShopPage::TRENDS.':'.ShopPage::TREND_MOSTDISCUSSED => Sii::t('sii','Trends - ').Sii::t('sii','Most Discussed Products'),
                ShopPage::TRENDS.':'.ShopPage::TREND_RECENTPURCHASED => Sii::t('sii','Trends - ').Sii::t('sii','Recent Purchased Products'),
                ShopPage::TRENDS.':'.ShopPage::TREND_RECENTLIKED => Sii::t('sii','Trends - ').Sii::t('sii','Recent Liked Products'),
                ShopPage::TRENDS.':'.ShopPage::TREND_RECENTDISCUSSED => Sii::t('sii','Trends - ').Sii::t('sii','Recent Discussed Products'),
            ]);
            logTrace(__METHOD__.' data',$this->_dpList);
        }
        return $this->_dpList;
    }   
    
    public function getMenuGroupList()
    {
        if (empty($this->_mList)){
            //Add all pages (online and offline)
            foreach (Page::model()->locateOwner($this->page->shopModel)->all()->findAll() as $model) {
                if ($model->isFullPage){
                    $this->_mList['Pages'][] = [
                        'id' => $model->id,
                        'type' => 'page',
                        'url' => $model->getUrl($this->page->https),
                        'label' => json_decode($model->title,true),
                    ];
                }
            }
            logTrace(__METHOD__.' data',$this->_mList);
        }
        return $this->_mList;
    }      
    
    public function getListItemList()
    {
        if (empty($this->_liList)){
            $this->_liList = [0=>Sii::t('sii','Select Item')];
            //Add news data provider list
            $this->_liList = array_merge($this->_liList,[
                static::$listItemProduct => Sii::t('sii','Product'),
                static::$listItemPromotion => Sii::t('sii','Promotion'),
                static::$listItemTrend => Sii::t('sii','Trend'),
            ]);
            logTrace(__METHOD__.' data',$this->_liList);
        }
        return $this->_liList;
    }           
    /**
     * This is used to support SGridListBlock
     * @see SGridListBlock
     * @param type $item
     * @return boolean
     */
    public function getPageByListItem($item)
    {
        switch (strtolower($item)) {
            case static::$listItemProduct:
                $filter = [];
                if ($this->page->hasFilter)
                    $filter = $this->page->filter->data;
                $page = $this->createPage(ShopPage::PRODUCTS,$filter);
                if (isset($this->page->sortby))
                    $page->sortby = $this->page->sortby;//handover soryby param if any
                return $page;
            case static::$listItemPromotion:
                $page = $this->createPage(ShopPage::PROMOTIONS);
                return $page;
            case static::$listItemTrend:
                $filter = ['topic'=>$this->page->trendTopic];
                $page = $this->createPage(ShopPage::TRENDS,$filter);
                return $page;
            default:
                return false;//no data provider found
        }     
    }       
}

