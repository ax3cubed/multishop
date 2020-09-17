<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CommunityModule
 *
 * @author kwlok
 */
class CommunityModule extends SModule 
{
    /**
     * @property string the default controller.
     */
    public $entryController = 'portal';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
              'class'=>'common.components.behaviors.AssetLoaderBehavior',
              'name'=>'community',
              'pathAlias'=>'community.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'community.components.*',
            'community.models.*',
            'common.widgets.spagelayout.SPageLayout',
            'common.widgets.spageindex.controllers.SPageIndexController',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'tutorials'=>[
                    'common.modules.tutorials.models.Tutorial',
                ],
                'questions'=>[
                    'common.modules.questions.models.Question',
                ],
                'tags'=>[
                    'common.modules.tags.models.Tag',
                ],
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ],
            'views'=>[
                'search'=>'application.modules.community.views.portal._searchbar',
                'tutorials'=>'application.modules.community.views.portal._tutorials',
                'questions'=>'application.modules.community.views.portal._questions',
                'tutorialseries'=>'application.modules.community.views.portal._tutorialseries',
                'topic'=>'application.modules.community.views.topics._topic',
                'topicslist'=>'application.modules.community.views.topics._topics',
                'tutoriallist'=>'application.modules.community.views.tutorials._tutorial_listview',
                'tutorialserieslist'=>'application.modules.community.views.tutorialSeries._tutorialseries_listview',
                'questionlist'=>'application.modules.community.views.questions._question_listview',
                //comments views
                'commentview'=>'comments.commentquickview',
                'commentform'=>'comments.commentform',
            ],
        ]);             
        
        $this->defaultController = $this->entryController;

        $this->registerScripts();
        $this->registerFormCssFile();
        $this->registerPagerCssFile();
        $this->registerMaterialIcons();
        
        //load process css
        $this->registerProcessCssFile();
        
        $this->registerSearchScript(true);//mainly to use its css
        
    }

}