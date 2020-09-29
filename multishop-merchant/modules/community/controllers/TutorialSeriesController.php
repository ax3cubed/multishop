<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of TutorialSeriesController
 *
 * @author kwlok
 */
class TutorialSeriesController extends CommunityBaseController 
{
    public function init()
    {
        parent::init();
        $this->prevdataMessage = Sii::t('sii','View previous comments');
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'TutorialSeries';
        $this->modelFilter = 'published';
        $this->viewName = $this->renderPartial('_header',[],true);
        $this->route = 'community/tutorials/series';
        $this->sortAttribute = 'update_time';
        $this->enableViewOptions = false;
        //-----------------//
        //set page seo
        $this->loadPageSeoSettings('community_series');
    }
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'rights - index, view', 
        ];
    }    
    /**
     * View tutorial series page
     * 
     * @see url mapper at main.php
     * @param string $slug
     */
    public function actionView($slug)
    {
        if (isset($slug)){
            $model = TutorialSeries::model()->published()->findByAttributes(['slug'=>$slug]);  
            if ($model===null)
                $this->loadErrorPage(404);
            else {
                $this->pageTitle = $model->getSeoTitle(user()->getLocale()).' | '.Sii::t('sii','Community');
                $this->metaDescription = $model->getSeoDesc(user()->getLocale());
                $this->metaKeywords = $model->getSeoKeywords();
                $this->render('view',['model'=>$model]);
            }
            Yii::app()->end();
        }
        $this->loadErrorPage(400);
    }    

}