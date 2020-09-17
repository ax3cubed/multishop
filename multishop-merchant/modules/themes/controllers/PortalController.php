<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('themes.controllers.ThemeViewControllerTrait');
Yii::import("wcm.controllers.WcmLayoutTrait");
/**
 * Description of PortalController
 *
 * @author kwlok
 */
class PortalController extends SSiteController
{
    use ThemeViewControllerTrait, WcmLayoutTrait;
    
    protected $themeGroup = Tii::GROUP_SHOP;
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        $this->loadWcmLayout($this);
        $this->htmlBodyCssClass .= ' theme-store';
        $this->setShopAssetsPathAlias();
    }     
    /**
     * Behaviors required to load shop resources path alias
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'shopassetsbehavior' => [
                'class'=>'common.modules.shops.behaviors.ShopAssetsBehavior',
            ],            
        ]);
    }    
    /**
     * Specifies the local access control rules.
     * @see SSiteController::accessRules()
     * @return array access control rules
     */
    public function accessRules()
    {
        return array_merge([
            ['allow',  
                'actions'=>['index','view'],
                'users'=>['*'],
            ]
        ],parent::accessRules());//parent access rules has to put at last
    }   
    
    public function actionIndex()
    {
        $this->pageTitle = Sii::t('sii','Theme Store'); 
        $this->render('index',['dataProvider'=>$this->dataProvider]);
    }
    
    public function actionView()
    {
        $theme = isset($_GET['theme_name']) ? $_GET['theme_name'] : null;
        $model = Theme::model()->locateTheme($theme)->find();
        if ($model==null)
            $this->redirect(url('themes'));
        else {
            $this->pageTitle =  $model->displayLanguageValue('name',user()->getLocale()).' | '.Sii::t('sii','Theme Store'); 
            $this->render('view',['model'=>$model]);
        }
    }    
    /**
     * Only select online theme for portal display
     * @return \CActiveDataProvider
     */
    public function getDataProvider()
    {
        return Tii::searchThemes($this->themeGroup);
    }  
 
    public function getAccessUrl()
    {
        if (user()->isGuest)
            return Yii::app()->urlManager->createHostUrl('signin');
        else
            return url('shop/themes');
    }
}
