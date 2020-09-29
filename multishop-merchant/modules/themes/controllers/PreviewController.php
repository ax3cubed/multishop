<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of PreviewController
 *
 * @author kwlok
 */
class PreviewController extends ThemeBaseController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //Preview will not show header 
        $this->htmlBodyCssClass = 'theme-preview';
        $this->headerView = null;
        $this->footerView = 'themes.views.preview._footer';
        $this->registerFontAwesome();
    }
    /**
     * Preview handler 
     * Preview url format:
     * https://<merchant_domain>/themes/preview/<theme>/<style>?page=<page_id>
     * @see ThemeBaseController::parseUri() for query parsing
     */
    public function actionIndex()
    {
        $this->render('index',[
            'theme'=>isset($this->theme) ? $this->theme->localeName(user()->getLocale()) : Sii::t('sii','Theme'),
            'style'=>isset($this->theme) ? $this->theme->getStyle($this->style)->getName(user()->getLocale()) : Sii::t('sii','Style'),
        ]); 
    }
    /**
     * @return string Page preview url for theme and style
     */
    protected function getPagePreviewUrl()
    {
        return $this->page->getPreviewUrl($this->theme->theme, $this->style);
    }
    
    protected function getShowFrame()
    {
        return isset($this->page) && isset($this->theme);
    }
    
    protected function getMessage()
    {
        return Sii::t('sii','No preview available');
    }
    
}
