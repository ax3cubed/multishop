<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shopwidgets.shopmessenger.ShopMessenger');
Yii::import('common.modules.notifications.models.NotificationScope');
Yii::import('common.modules.chatbots.payloads.OptInPayload');
/**
 * Description of ShopSendToMessenger
 *
 * @author kwlok
 */
class ShopSendToMessenger extends ShopMessenger
{
    protected $optIn;
    /**
     * Get shop messenger plugin
     */
    public function getPlugin()
    {
        if ($this->shopModel->messenger!=null && $this->shopModel->messenger->isPluginSendToMessengerEnabled)
            return ShopMessenger::SEND_TO_MESSENGER;
        else
            return null;
    }        
    /**
     * Get stuff that can be subscribed
     */
    public function getOptInPayload()
    {
        if (!isset($this->optIn)){
            $scope = new NotificationScope($this->shopModel->id, get_class($this->shopModel));
            $this->optIn = new OptInPayload(OptInPayload::OPT_IN, $scope, user()->isGuest?Account::GUEST:user()->getId());
        }
        return $this->optIn;
    }        
    /**
     * Get view data
     * @return array
     */
    public function getPluginViewData()
    {
        return [
            'appId'=>$this->shopModel->messenger->messengerAppId,
            'pageId'=>$this->shopModel->messenger->messengerPageId,
            'dataRef'=>$this->optInPayload->toString(),
            'updates'=>$this->optInPayload->notificationDisplayNames,
        ];
    }  
}
