<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of PortalController
 *
 * @author kwlok
 */
class PortalController extends CommunityBaseController 
{
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->customWidgetView = true;
        $this->pageControl = null;//not set
        $this->modelType = 'Tutorial';//dummy model
        //set page seo
        $this->loadPageSeoSettings('community_portal');
        //-----------------//
    }
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'rights - index, prevdata, search', 
        ];
    }   
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'searchbehavior' => [
                'class'=>'common.modules.search.behaviors.SearchControllerBehavior',
                'targets'=>['SearchTutorial','SearchQuestion','SearchTutorialSeries'],
                'filter'=>['callback'=>'getSearchFilter'],
                'searchFields'=>['title','tags','content','question','name'],
                'placeholder'=>Sii::t('sii','Search community'),
                'onsearch'=>$this->onsearch,
                'loadSearchbar'=>false,
            ],
        ]);
    }      
    /**
     * A callback method to get search filter
     * @see SearchControllerBehavior
     */
    public function getSearchFilter()
    {
        return [
            'status'=>SearchFilter::ACTIVE,
        ];
    }    
    /**
     * Search community
     * @param type $query
     * @param type $page
     */
    public function actionSearch() 
    {
        $query = '';//empty search text for initial
        if (isset($_GET['q'])){
            $query = $_GET['q'];
            $response = $this->parseQuery($query, isset($_GET['page'])?$_GET['page']:null);
        }   
            
        $this->loadPageSeoSettings('community_portal','pageTitle');
        
        $this->render('search',['query'=>$query,'response'=>isset($response)?$response:$this->getSearchEmptyResponse()]);
    }    
    /**
     * OVERRIDE METHOD, since we set $customWidgetView to true in init()
     * 
     * @see SPageIndexController
     * @return string view file name
     */
    public function getWidgetView($view,$scope=null,$searchModel=null)
    {
        return $this->render('index',[],true);
    }   
    /**
     * Render callout widget
     * @param type $config
     */
    public function calloutWidget($config)
    {
        $this->renderPartial('wcm.views.content._callout',['callout'=>$config]);
    }  
    
    public function getPages()
    {
        $pageSize = Config::getSystemSetting('record_per_page');
        $pages = new CMap();   
        $pages->add('tutorials_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Tutorials').'</div>',
            'view'=>'_tutorials',
            'data'=>['dataProvider'=>new CActiveDataProvider(Tutorial::model()->published(),[
                'criteria'=>[
                    'order'=>'create_time DESC',
                ],
                'pagination'=>[
                    'pageSize'=>$pageSize,
                    'route'=>'tutorials/index',
                ],
                'sort'=>false,
            ])],
        ]);
        $pages->add('tutorial_series_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Tutorial Series').'</div>',
            'view'=>'_tutorialseries',
            'data'=>['dataProvider'=>new CActiveDataProvider(TutorialSeries::model()->published(),[
                'criteria'=>[
                    'order'=>'create_time DESC',
                ],
                'pagination'=>[
                    'pageSize'=>$pageSize,
                    'route'=>'tutorials/series',
                ],
                'sort'=>false,
            ])],
        ]);        
        $pages->add('questions_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Questions').'</div>',
            'view'=>'_questions',
            'data'=>['dataProvider'=>new CActiveDataProvider(Question::model()->published(),[
                'criteria'=>[
                      'order'=>'question_time DESC',
                    ],
                'pagination'=>[
                    'pageSize'=>$pageSize,
                    'route'=>'questions/index',
                ],
                'sort'=>false,
            ])],
        ]);
        $pages->add('topics_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Topics').'</div>',
            'view'=>$this->module->getView('topicslist'),
            'data'=>['dataProvider'=>$this->getTagsDataProvider()],
        ]);
        return $pages->toArray();
                
    }
}