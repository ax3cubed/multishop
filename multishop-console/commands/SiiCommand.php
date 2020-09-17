<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("system.cli.commands.MessageCommand");
/**
 * This SiiCommand will auto generate sii.php (translation files) of respective messages sources (apps, modules etc)
 * and place under respective folder under "common/messages/_translate"
 * 
 * It calls internally Yii build-in MessageCommand
 *
 * @author kwlok
 */
class SiiCommand extends SCommand 
{
    private $_config;
    public function init() 
    {
        $basepath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'sii';
        $this->_config = array(
            'all'=>$basepath.'/all/config.php',
            'common'=>$basepath.'/common/config.php',
            //apps
            'customer'=>$basepath.'/apps/customer/config.php',
            'merchant'=>$basepath.'/apps/merchant/config.php',
            'admin'=>$basepath.'/apps/admin/config.php',
            'shop'=>$basepath.'/apps/shop/config.php',
            'api'=>$basepath.'/apps/api/config.php',
            'bot'=>$basepath.'/apps/bot/config.php',
            //modules
            'accounts'=>$basepath.'/modules/accounts/config.php',
            'activities'=>$basepath.'/modules/activities/config.php',
            'analytics'=>$basepath.'/modules/analytics/config.php',
            'billings'=>$basepath.'/modules/billings/config.php',
            'carts'=>$basepath.'/modules/carts/config.php',
            'chatbots'=>$basepath.'/modules/chatbots/config.php',
            'comments'=>$basepath.'/modules/comments/config.php',
            'community'=>$basepath.'/modules/community/config.php',
            'customers'=>$basepath.'/modules/customers/config.php',
            'help'=>$basepath.'/modules/help/config.php',
            'items'=>$basepath.'/modules/items/config.php',
            'media'=>$basepath.'/modules/media/config.php',
            'messages'=>$basepath.'/modules/messages/config.php',
            'news'=>$basepath.'/modules/news/config.php',
            'notifications'=>$basepath.'/modules/notifications/config.php',
            'orders'=>$basepath.'/modules/orders/config.php',
            'payments'=>$basepath.'/modules/payments/config.php',
            'plans'=>$basepath.'/modules/plans/config.php',
            'questions'=>$basepath.'/modules/questions/config.php',
            'search'=>$basepath.'/modules/search/config.php',
            'shops'=>$basepath.'/modules/shops/config.php',
            'likes'=>$basepath.'/modules/likes/config.php',
            'tags'=>$basepath.'/modules/tags/config.php',
            'tasks'=>$basepath.'/modules/tasks/config.php',
            'tutorials'=>$basepath.'/modules/tutorials/config.php',
            'wallets'=>$basepath.'/modules/wallets/config.php',
            'wcm'=>$basepath.'/modules/wcm/config.php',
            //merchant modules
            'attributes'=>$basepath.'/modules/attributes/config.php',
            'brands'=>$basepath.'/modules/brands/config.php',
            'campaigns'=>$basepath.'/modules/campaigns/config.php',
            'inventories'=>$basepath.'/modules/inventories/config.php',
            'products'=>$basepath.'/modules/products/config.php',
            'shippings'=>$basepath.'/modules/shippings/config.php',
            'taxes'=>$basepath.'/modules/taxes/config.php',
            //customer modules - none
        );
        parent::init();
    }
    
    public function actionIndex($category=null) 
    {   
        if ($category!=null){
            if (array_key_exists($category, $this->_config)){
                $this->logInfo('Run sii command for '.$this->_config[$category]);
                $this->_runMessageCommand($this->_config[$category]);
                $this->logInfo('Category '.strtoupper($category).' ok');
            }
            else {
                $this->logError('Category '.$category.' not found');
                return 1;//error
            }
        }
        else {
            foreach ($this->_config as $category => $config) {
                $this->logInfo('Run sii command for '.$config);
                $this->_runMessageCommand($config);
                $this->logInfo('Category '.strtoupper($category).' ok');
            }
        }
    }
    
    private function _runMessageCommand($config) 
    {
        $command = new MessageCommand(__CLASS__,new CConsoleCommandRunner());
        $command->run(array($config));
    }
}
