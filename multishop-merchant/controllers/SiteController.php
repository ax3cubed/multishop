<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("wcm.controllers.WcmLayoutTrait");
/**
 * Description of SiteController
 *
 * @author kwlok
 */
class SiteController extends SSiteController 
{  
    use WcmLayoutTrait;
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        $this->loadWcmLayout($this);
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
                'actions'=>['index','maintenance'],
                'users'=>['*'],
            ]
        ],parent::accessRules());//parent access rules has to put at last
    }    
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     * 
     * It supports locale setting directly via url
     * E.g. Send in ?lang=zh_cn for chinese
     */
    public function actionIndex()
    {
        if (isset($_GET['lang'])){
            $this->setUserLocale($_GET['lang']);
        }
        
        if (user()->isGuest) {
            //@see MerchantUrlManager::$wcmRules
            $this->redirect('index');//pointing to wcm/content/index
            //user()->loginRequired();
            Yii::app()->end();  
        }
        $this->redirect('welcome');
    }
    /**
     * This method is added mainly to support the clearing of cache
     * when apps are distributed
     * @param $ops maintenance operation; currently support clearing cache
     */
    public function actionMaintenance($ops)
    {
        $task = explode('.', $ops);
        if (is_array($task)&&isset($task[0])&&isset($task[1])&&$task[0]==SCache::REMOTE_DELETE_TOKEN){
            Yii::app()->commonCache->localDelete($task[1]);
            logInfo(__METHOD__.' delete local cache '.$task[1].' ok',request()->getServerName().request()->getRequestUri());
            Yii::app()->end();
        }
        else
            throwError404(Sii::t('sii','Page not found'));
    }
    
}