<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of QuestionsController
 *
 * @author kwlok
 */
class QuestionsController extends CommunityBaseController 
{
    public function init()
    {
        parent::init();
        $this->prevdataMessage = Sii::t('sii','View previous answers');
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Question';
        $this->modelFilter = 'published';
        $this->viewName = $this->renderPartial('_header',[],true);
        $this->route = 'community/questions/index';
        $this->sortAttribute = 'question_time';
        $this->enableViewOptions = false;
        //set page seo
        $this->loadPageSeoSettings('community_questions');
        //-----------------//
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
     * View question page
     * 
     * @see url mapper at main.php
     * @param string $slug
     */
    public function actionView($slug)
    {
        if (isset($slug)){
            $model = Question::model()->published()->findByAttributes(['slug'=>$slug]);  
            if ($model===null)
                $this->loadErrorPage(404);
            else {
                $this->pageTitle = $model->title.' | '.Sii::t('sii','Community');
                $this->render('view',['model'=>$model]);
            }
            Yii::app()->end();
        }
        $this->loadErrorPage(400);
    }    
    /**
     * Get previous answers
     */
    public function actionPrevAnswers()
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
            $prevlink = $this->renderPartial($this->module->getView('shops.product'.$idx.'prev'),[$idx.'Form'=>$form],true);

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

}