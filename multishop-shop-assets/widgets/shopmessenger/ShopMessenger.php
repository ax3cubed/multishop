<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopMessenger
 *
 * @author kwlok
 */
class ShopMessenger extends SWidget
{
    CONST MESSAGE_US = 'message_us';
    CONST SEND_TO_MESSENGER = 'send_to_messenger';
    /**
     * The path alias to access assets
     * @property string
     */
    public $pathAlias = 'shopwidgets.shopmessenger.assets';
    /**
     * string the asset name of the widget
     */
    public $assetName = 'shopmessenger';
    /**
     * CActiveRecord the shop model
     */
    public $shopModel;
    /**
     * If to wrap the widget within a contaner (border box)
     * @boolean Default to true
     */
    public $container = true;
    /**
     * If to show welcome view
     * @var If null, not showing anything; Set the view file containing the welcome text
     */
    public $welcomeView = '_message_us_welcome_text';
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if ($this->hasPlugin){
            try {
                $this->render('index');
            } catch (Exception $ex) {
                logError(__METHOD__.' unable to load messenger plugin',$ex->getMessage());
            }
        }
    }
    /**
     * Check if has plugin
     * @return boolean
     */
    public function getHasPlugin()
    {
        return $this->plugin!=null;
    }
    /**
     * Get shop messenger plugin
     */
    public function getPlugin()
    {
        if ($this->shopModel->messenger!=null && $this->shopModel->messenger->isPluginMessageUsEnabled)
            return ShopMessenger::MESSAGE_US;
        else
            return null;
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
            'welcomeView'=>$this->welcomeView,
        ];
    }
    /**
     * Function to publish and register assets on page 
     * @see SAssetManager::prepareShopWidgetsAssetBundle() 
     * @throws CException
     */
    public function publishAssets()
    {
        //Skip; Shop widgets assets files loading is triggered by changes in theme assets.
    }     
}
