<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of TopicsController
 *
 * @author kwlok
 */
class TopicsController extends CommunityBaseController 
{
    public function init()
    {
        parent::init();
    }    
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'rights - index, search', 
        ];
    }    
    /**
     * Index page
     * 
     * @see url mapper at main.php
     * @param string $slug
     */
    public function actionIndex()
    {
        $this->loadPageSeoSettings('community_topics','pageTitle');
        $this->render('index',['dataProvider'=>$this->getTagsDataProvider()]);
        Yii::app()->end();
    }      
    /**
     * Search by tutorials/questions by tag
     * 
     * @see url mapper at main.php
     * @param string $topic
     */
    public function actionSearch($topic)
    {
        $this->loadPageSeoSettings('community_topics','pageTitle');
        if (isset($topic)){
            $this->render('search',['topic'=>urldecode($topic)]);
            Yii::app()->end();
        }
        throwError400(Sii::t('sii','Bad request'));
    }   
    
    public function getPages($topic)
    {
        $pages = new CMap();   
        //for now use db search; when elasticsearch is ready, should use elasticsearch
        
        //[1]Tutorial data provider
        $tCriteria = new CDbCriteria();
        $tCriteria->compare('tags', $topic,true);
        $tCriteria->order = 'create_time DESC';
        $tDataProvider = new CActiveDataProvider(Tutorial::model()->published(),[
                            'criteria'=>$tCriteria,
                            'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
                            'sort'=>false,
                        ]);
        $pages->add('tutorials_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Tutorials').' ('.$tDataProvider->getTotalItemCount().')'.'</div>',
            'view'=>$this->module->getView('tutorials'),
            'data'=>['dataProvider'=>$tDataProvider],
        ]);
        //[2]TutorialSeries data provider
        $tsDataProvider = new CActiveDataProvider(TutorialSeries::model()->published(),[
                            'criteria'=>$tCriteria,
                            'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
                            'sort'=>false,
                        ]);
        $pages->add('tutorial_series_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Tutorial Series').' ('.$tsDataProvider->getTotalItemCount().')'.'</div>',
            'view'=>$this->module->getView('tutorialseries'),
            'data'=>['dataProvider'=>$tsDataProvider],
        ]);
        //[3]Question data provider
        $qCriteria = new CDbCriteria();
        $qCriteria->compare('tags', $topic,true);
        $qCriteria->order = 'question_time DESC';
        $qDataProvider = new CActiveDataProvider(Question::model()->published(),[
                            'criteria'=>$qCriteria,
                            'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
                            'sort'=>false,
                        ]);
        
        $pages->add('questions_tab',[
            'title'=>'<div class="title">'.Sii::t('sii','Questions').' ('.$qDataProvider->getTotalItemCount().')'.'</div>',
            'view'=>$this->module->getView('questions'),
            'data'=>['dataProvider'=>$qDataProvider],
        ]);
        
        return $pages->toArray();
                
    }
    
}
