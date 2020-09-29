<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.notifications.models.*");
Yii::import("common.services.notification.*");
Yii::import("common.services.notification.events.*");
Yii::import("console.components.ConsoleController");
/**
 * Description of NotificationBaseCommand
 * 
 * @author kwlok
 */
abstract class NotificationBaseCommand extends SCommand 
{
    /**
     * Limit to 1000 queue items per run
     * @var type 
     */
    protected static $batchSize = 1000;

    private $_q;//queue
    private $_d;//dispatcher
    private $_c;//controller
    
    public function init() 
    {
        $this->_q = new CQueue();
        $this->_d = new Dispatcher();
        $this->_c = new ConsoleController('notification');
        parent::init();
    }
    
    public function getQueue()
    {
        return $this->_q;
    }
    
    public function getDispatcher()
    {
        return $this->_d;
    }
    
    public function getController()
    {
        return $this->_c;
    }

}
