<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.components.ShopPage');
Yii::import('shopwidgets.shopsitemap.SitemapTrait');
/**
 * Description of ShopSitemap
 *
 * @author kwlok
 */
class ShopSitemap extends SWidget
{
    use SitemapTrait;
    /**
     * The path alias to access assets
     * @property string
     */
    public $pathAlias = 'shopwidgets.shopsitemap.assets';
    /**
     * string the asset name of the widget
     */
    public $assetName = 'shopsitemap';
    /**
     * Individual page model loading
     * @param type $model
     */
    protected function loadSitemapModel($groupName,$record)
    {
        if ($record instanceof Category){//auto pickup sub category as well
            //only display those categories having products
            if (ProductCategory::model()->locateCategory($record->id)->exists()){
                $this->modelItems[$groupName][] = $this->getUrlRecord($record);
                $subcategories = CategorySub::model()->locateCategory($record->id)->findAll();
                foreach( $subcategories as $subcategory ) {
                    $this->modelItems[$groupName][] = $this->getUrlRecord($subcategory,$subcategory->toString(user()->getLocale(),'>'));
                }
            }
        }
        elseif ($record instanceof Brand){
            //only display those brands having products
            if (Product::model()->locateBrand($record->id)->exists()){
                $this->modelItems[$groupName][] = $this->getUrlRecord($record);
            }
        }
        else {
            $this->modelItems[$groupName][] = $this->getUrlRecord($record);
        }
    }    
    /**
     * Parse how to present model name
     * @param $model
     * @param $locale
     * @return mixed
     */
    protected function parseModelName($model,$locale)
    {
        if ($model instanceof Campaign)
            return $model->getCampaignText($locale);
        elseif ($model instanceof News)
            return $model->displayLanguageValue('headline',$locale);
        else
            return $model->displayLanguageValue('name',$locale);
    }
    /**
     * Sitemap config
     * 
     * @param type $page
     * @param type $contentType
     * @return type
     */
    public static function getDefaultConfig($page,$contentType)
    {
        $customPages = [
            [
                'model' => 'Page',//model to be places under Pages category; Which is custom Page model
            ],
        ];
        
        $inbuiltPages = [];
        foreach (ShopPage::inBuiltPages() as $p) {
            $inbuiltPages[] = [
                                'name'=>ShopPage::getTitle($p),
                                'loc'=>$page->getUrl($p),
                                'priority'=>'0.5',
                            ];
        }
        
        return [
            'contentType'=>$contentType,
            'pageOwner'=>$page->shopModel,
            'pageObject'=>$page,
            'pages'=>array_merge($customPages,$inbuiltPages),
            'models'=>['Category','Brand','Product','CampaignBga','News',],
        ];
    }
}
