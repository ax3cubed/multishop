<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SitemapTrait
 *
 * @author kwlok
 */
trait SitemapTrait 
{
    /**
     * The owner (model) owns the page
     */
    public $pageOwner;
    /**
     * The page object owns the sitemap
     */
    public $pageObject;
    /**
     * Sitemap output content type: Either 'application/xml' or 'text/html'
     * Default to "text/html"
     */
    public $contentType = 'text/html';
    /**
     * Static pages to be included into sitemap.
     */
    public $pages = [];
    /**
     * Models to be included into sitemap.
     * The module must have scope name called "sitemap"
     */
    public $models = [];
    /**
     * Sitemap items; They are auto populated when $pages and $models are defined;
     */
    protected $pageItems = [];
    protected $modelItems = [];    
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if ($this->pageOwner->hasSitemap()){
            $this->populateSitemap();
            if ($this->contentType=='application/xml')
                $this->render('xml');
            else
                $this->render('html');
        }
        else
            echo Sii::t('sii','Page not found');
    }    
    /**
     * Populate the array of site links
     */
    public function populateSitemap()
    {
        //in-built pages
        foreach( $this->pages as $page ) {
            if (isset($page['model'])){
                //custom pages
                $customPages = $page['model']::model()->sitemap($this->pageOwner->id,get_class($this->pageOwner))->findAll();
                foreach( $customPages as $index => $record ) {
                    if ($record->isFullPage)
                        $this->pageItems[] = $this->getUrlRecord($record);
                }            
            }
            else {
                $this->pageItems[] = $page;
            }
        }
        //other models
        foreach( $this->models as $model ) {
            $this->populateModelSitemap($model);
        }
    }
    /**
     * Load page models into sitemap
     * @param type $model
     */
    protected function populateModelSitemap($model)
    {
        $records = $model::model()->sitemap($this->pageOwner->id)->findAll();
        foreach( $records as $index => $record ) {
            if ($index==0)
                $groupName = $record->displayName(Helper::PLURAL);//get locale name

            $this->loadSitemapModel($groupName,$record);
        }    
    }    
    /**
     * Individual page model loading
     * @param type $model
     */
    protected function loadSitemapModel($groupName,$record)
    {
        $this->modelItems[$groupName][] = $this->getUrlRecord($record);
    }
    /**
     * Formulate url record
     * @param $record
     * @param null $name
     * @return array
     */
    protected function getUrlRecord($record,$name=null)
    {
        return [
            'name'=>isset($name)?$name:$this->parseModelName($record,user()->getLocale()),
            'loc'=> $this->pageObject->appendExtraQueryParams($record->getUrl($this->pageObject->https)),
            'lastmod'=>date('Y-m-d',$record->update_time),
            'frequency'=>'always',
            'priority'=>'0.8',
        ];
    }
    /**
     * Parse how to present model name
     * @param $model
     * @param $locale
     * @return mixed
     */
    protected function parseModelName($model,$locale)
    {
        return $model->localeName($locale);
    }
}
