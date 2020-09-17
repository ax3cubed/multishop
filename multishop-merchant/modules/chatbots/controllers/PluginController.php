<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of PluginController
 *
 * @author kwlok
 */
class PluginController extends AuthenticatedController
{
    protected $formModel = 'ChatbotPluginForm';
    /**
     * Init
     */
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Chatbot Plugin');
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'update'=>[
                'class'=>'chatbots.actions.UpdateSettingsAction',
                'formModel'=>$this->formModel,
            ],                   
        ]);
    }    
}
