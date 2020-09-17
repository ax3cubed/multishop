<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.wcm.models.WcmContentTrait');
Yii::import('wcm.controllers.WcmLayoutTrait');
/**
 * Description of CommunityBaseController
 *
 * @author kwlok
 */
class CommunityBaseController extends SPageIndexController 
{
    use WcmContentTrait, WcmLayoutTrait;
    
    public $onsearch = 'communitysearch()';
    public $prevdataMessage;
    public $prevdataRoute = '/community/portal/prevdata';
    /**
     * Change the header sitelogo text and link
     */
    public function init()
    {
        parent::init();
        $this->loadLayout();
        $this->pageHeading = Sii::t('sii','Community');
        $this->checkLanguage();
    }
    /**
     * Get previous comments
     */
    public function actionPrevdata()
    {
        if(isset($_POST['CommentForm'])) {
            $form = new CommentForm('prevcomment');
            $form->attributes = $_POST['CommentForm'];
            logTrace(__METHOD__.' CommentForm',$form->getAttributes());
        }
        
        $idx = 'comment';
        $model = $this->loadModel($form->target,$form->type,false);
        $dataProvider = $model->searchComments($form->page);
        
        $form->page += 1;
        if ($dataProvider->pagination->pageSize>=$dataProvider->totalItemCount)
            $prevlink = 'onepage';//question pagination is showing reverse
        else if ($dataProvider->pagination->currentPage==0)
            $prevlink = 'lastpage';//question pagination is showing reverse
        else
            $prevlink = $this->renderPartial($this->module->getView('shops.product'.$idx.'prev'),array($idx.'Form'=>$form,'message'=>$this->prevdataMessage),true);

        header('Content-type: application/json');
        echo CJSON::encode([
            'prevlink'=>$prevlink,
            'prevdata'=>$this->widget('zii.widgets.CListView', [
                    'dataProvider'=>$dataProvider,
                    'template'=>'{items}',
                    'emptyText'=>'',
                    'itemView'=>$this->module->getView($idx.'s.'.$idx.'quickview'),
                ],true),
         ]);
         Yii::app()->end();      
    }    
    /**
     * Get tags data provider
     * @return \CArrayDataProvider
     */
    public function getTagsDataProvider()
    {
        $pageSize = 100;//show 100 tags each group (large enough). But exceeding this tags will not be displayed
        //todo Tags pagination to be supported
        $tags = new CMap();
        //[1] First load tutorials
        $tags = $this->_prepareTagsData($tags, new CActiveDataProvider(Tutorial::model()->published(),[
                            'criteria'=>[
                                'order'=>'create_time DESC',
                            ],
                            'pagination'=>[
                                'pageSize'=>$pageSize,
                            ],
                            'sort'=>false,
                        ]));
        //[2] First load tutorial series
        $tags = $this->_prepareTagsData($tags, new CActiveDataProvider(TutorialSeries::model()->published(),[
                            'criteria'=>[
                                'order'=>'create_time DESC',
                            ],
                            'pagination'=>[
                                'pageSize'=>$pageSize,
                            ],
                            'sort'=>false,
                        ]));
        //[3] Second load questions
        $tags = $this->_prepareTagsData($tags, new CActiveDataProvider(Question::model()->published(),[
                            'criteria'=>[
                                'order'=>'question_time DESC',
                            ],
                            'pagination'=>[
                                'pageSize'=>$pageSize,
                            ],
                            'sort'=>false,
                        ]));
        //logTrace(__METHOD__,$tags->toArray());
        //aggreate counter for same action before return
        return new CArrayDataProvider(Helper::aggregateArrayValues($tags, 'tag', 'count'),['keyField'=>false]);
    }
    
    private function _prepareTagsData($tags,$dataProvider)
    {
        $list = Tag::getList(user()->getLocale());
        foreach($dataProvider->data as $data) {
            foreach (explode(',', $data->tags) as $value) {
                if ($tags->contains($value)){
                    $tag = $tags->itemAt($value);
                    $count = $tag['count'] + 1;
                }
                else {
                    $count = 1;
                }
                
                if (!empty($value)){
                    if (isset($list[$value]))//only show when tag key is found (in case tag is changed)
                        $tags->add($value,[
                            'count'=>$count,
                            'tag'=>$list[$value],
                            'url'=>Tag::model()->getUrl($value),
                        ]);
                }
            }
        }    
        return $tags;
    }
    public function getCommentsDataProvider($model)
    {
        return $model->searchComments();
    }

    public function getCommentForm($model)
    {
        return new CommentForm(CommentForm::SCENARIO_COUNTER,get_class($model),$model->id);
    }

    public function getLikeForm($model)
    {
        return new LikeForm(get_class($model),$model->id);
    }
    
    public function getCanonicalUrl()
    {
        return app()->urlManager->createHostUrl('/community',true);
    }
    /**
     * This method helps to find the community page seo settings. 
     * It uses {@link WcmPage} to store its seo page header settings. 
     * Community seo are configured at Admin web app.
     * @see WcmPage
     * @see WcmControllerTrait
     * @param type $page
     */
    public function loadPageSeoSettings($page,$pageTitleAttribute='pageHeading')
    {
        $seoTitle = $this->getPageSeo($page, 'seoTitle');
        //here use $pageHeading instead of $pageTitle is to follow SPageIndexController
        $this->{$pageTitleAttribute} = strlen($seoTitle) > 0 ? $seoTitle: Sii::t('sii','Community'); 
        $this->metaDescription = $this->getPageSeo($page, 'seoDesc');
        $this->metaKeywords = $this->getPageSeo($page, 'seoKeywords');
    }
    
    protected function loadLayout()
    {
        $this->layout = 'community.views.layouts.community';
        $this->headerView = 'community.views.layouts._community_header';
        $this->footerView = 'community.views.layouts._community_footer';
        $this->htmlBodyCssClass .= ' community';
    }
    
    protected function loadErrorPage($code=404)
    {
        $this->loadLayout();
        $this->colorScheme = WcmLayoutTrait::$colorSchemeBlack;
        app()->ctrlManager->setColorScheme($this->colorScheme);
        switch ($code) {
            case 400:
                throwError400(Sii::t('sii','Bad request'));
                break;
            default:
                throwError404(Sii::t('sii','Page not found.'));
                break;
        }
    }    
}
