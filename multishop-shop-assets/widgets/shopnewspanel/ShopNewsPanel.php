<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.SWidget");
/**
 * Description of ShopNewsPanel
 *
 * @author kwlok
 */
class ShopNewsPanel extends SWidget
{
    /**
     * The path alias to access assets
     * @property string
     */
    public $pathAlias = 'shopwidgets.shopnewspanel.assets';
    /**
     * string the asset name of the widget
     */
    public $assetName = 'shopnewspanel';
    /**
     * ShopViewPage the current page object
     */
    public $page;
    /**
     * string the active menu item
     */
    public $locale;
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if ($this->hasNews){
            $this->render('index'); 
        }        
    }    
    
    private $_news;
    public function getHasNews()
    {
        if (!isset($this->_news)){
            $this->_news = $this->page->shopModel->searchNews(Process::NEWS_ONLINE);
        }
        return $this->_news->getTotalItemCount()>0;
    }      
    
    public function getNews()
    {
        $news = new CList();
        foreach ($this->_news->data as $data) {
            $heading = $data->displayLanguageValue('headline',$this->locale,Helper::PURIFY);
            $news->add([
                'label'=>Helper::rightTrim($heading,20).'<div class="date">'.Helper::prettyDate($data->create_time).'</div>',
                'url'=>$this->page->appendExtraQueryParams($data->getUrl($this->page->https)),
                'linkOptions'=>['title'=>$heading,'data-page'=>ShopPage::NEWS,'data-article'=>$data->id],
            ]);                
        }
        return $news->toArray();        
    }    
    
    public function getHeading()
    {
        return Sii::t('sii','Latest News {link}',[
            '{link}'=>CHtml::link(Sii::t('sii','More'),'javascript:void(0);',[
                'class'=>'more-news',
                'data-page'=>ShopPage::NEWS,
                //Need to use javascript as hack as the jui accordion heading since is auto wrapped by a link <a> 
                'onclick'=>'pageredirect("'.$this->page->getUrl(ShopPage::NEWS).'")',
            ]),
        ]);        
    }
            
}
