<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.SWidget");
/**
 * Description of ShopPromotions
 *
 * @author kwlok
 */
class ShopPromotions extends SWidget
{
    private $_d;//data provider
    /**
     * The path alias to access assets
     * @property string
     */
    public $pathAlias = 'shopwidgets.shoppromotions.assets';
    /**
     * string the asset name of the widget
     */
    public $assetName = 'shoppromotions';
    /**
     * ShopViewPage the shop page displaying this widget
     */
    public $page;
    /**
     * If to show heading; Default to true
     */
    public $showHeading = true;
    /**
     * If to show action bar; Default to false
     */
    public $showActionBar = false;
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if ($this->dataProvider->getTotalItemCount()>0){
            if ($this->showHeading)
                $this->render('heading'); 
        
            $this->render('index');
        }
    }    
    
    public function getDataProvider()
    {
        if (!isset($this->_d))
            $this->_d = $this->controller->getBgaCampaignsDataProvider($this->page->shopModel);
        
        return $this->_d;
    }
}
