<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('chatbots.models.ChatbotBaseForm');
/**
 * Description of ChatbotPluginForm
 *
 * @author kwlok
 */
class ChatbotPluginForm extends ChatbotBaseForm 
{
    protected $id = 'plugin';//the id
    public $appId;
    public $pageId;
    public $messageUsPlugin = 0;//default 1=yes (0=No)
    public $sendToMessengerPlugin = 0;//default 1=yes (0=No);
    /**
     * Validation rules 
     * @return array validation rules
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['appId, pageId', 'required'],
            ['appId, pageId', 'length', 'max'=>100],
            ['messageUsPlugin, sendToMessengerPlugin', 'boolean'],
        ]);
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge(parent::attributeToolTips(),[
            'appId'=>Sii::t('sii','Enter your Facebook App ID'),
            'pageId'=>Sii::t('sii','Enter your Facebook Page ID'),
        ]);
    }

    public function displayName() 
    {
        return Sii::t('sii','Plugin Settings');
    }
    
    public function getPluginImage($image)
    {
        $assetsURL = Yii::app()->controller->getAssetsURL('common.modules.chatbots.providers.messenger.assets');
        return $assetsURL.'/'.$image;
    }
}
