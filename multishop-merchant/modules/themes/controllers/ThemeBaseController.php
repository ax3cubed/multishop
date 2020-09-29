<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ThemeBaseController
 *
 * @author kwlok
 */
class ThemeBaseController extends AuthenticatedController
{
    /**
     * Property required to preview a page for theme
     */
    protected $uri;
    protected $theme;
    protected $style;
    protected $page;
    
    public function init()
    {
        parent::init();
        $this->parseUri();
    }
    /**
     * Parse uri
     * Expect format:
     * https://<merchant_domain>/themes/<controller>/<theme>/<style>?page=<page_id>
     */
    protected function parseUri()
    {
        $this->uri = Helper::parseUri();
        $this->page  = isset($this->uri['query']['page']) ? $this->findPage($this->uri['query']['page']) : null;
        $this->theme = isset($this->uri['path'][3]) ? $this->findTheme($this->uri['path'][3]) : null;
        $this->style = isset($this->uri['path'][4]) ? $this->uri['path'][4] : Tii::defaultStyle();//use default if not set
    }
   
    protected function findTheme($theme)
    {
        return Theme::model()->locateTheme($theme)->find();
    }

    protected function findPage($pageId)
    {
        return Page::model()->mine()->find('id='.$pageId);
    }
}
