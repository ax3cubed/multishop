<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.chatbots.ChatbotWebhookTrait');
/**
 * Description of ChatbotsModule
 *
 * @author kwlok
 */
class ChatbotsModule extends SModule 
{
    use ChatbotWebhookTrait;
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'chatbots',
                'pathAlias'=>'chatbots.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'chatbots.actions.*',
            'chatbots.models.*',
            'chatbots.controllers.*',
            'common.modules.chatbots.models.*',
        ]);

        // import module dependencies classes
        $this->setDependencies([
            'classes'=>[],
            'sii'=>[],
        ]);  

        //$this->defaultController = 'to_be_implemented';

        $this->registerScripts();
        
        if (!isset($this->webhookDomain))
            $this->webhookDomain = param('BOT_DOMAIN');
    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.ChatbotManager',
                'model'=>'Chatbot',
            ],
        ]);
        return $this->getComponent('servicemanager');
    }    
}
