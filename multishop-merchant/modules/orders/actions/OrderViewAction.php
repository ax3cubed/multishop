<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.components.actions.CRUDBaseAction");
/**
 * Description of OrderViewAction
 *
 * @author kwlok
 */
class OrderViewAction extends CRUDBaseAction 
{
    /**
     * Filter method of model
     * @var type 
     */
    public $modelFilter = 'mine';
    /**
     * The finder method for search; Default to 'findOrder' 
     * 
     * @see ShippingOrder::findOrder()
     * @var string 
     */
    public $finderMethod = 'findOrder';    
    /**
     * The account attribute used to check access rights; Default to null, following the value in ServiceManager
     * @see ServiceManager->ownerAttribute
     * 
     * @var string 
     */
    public $accountAttribute;
    /**
     * The view file of Purchase Order; Default to 'purchaseorder' 
     * 
     * @var string 
     */
    public $viewFilePO = 'purchaseorder';    
    /**
     * The view file of Shipping Order; Default to 'shippingorder' 
     * 
     * @var string 
     */
    public $viewFileSO = 'shippingorder';    
    /**
     * Run the action
     */
    public function run()             
    {
        logInfo('['.$this->controller->uniqueId.'/'.$this->controller->action->id.'] '.__METHOD__.' $_GET', $_GET);
        $orderNo = current(array_keys($_GET));//take the first key as search attribute
        $model = $this->_findModel($this->model,$orderNo,$this->modelFilter,$this->finderMethod);
        
        $serviceManager = $this->controller->module->getServiceManager();
        if (!isset($serviceManager))
            throw new CHttpException(500,Sii::t('sii','Service Not Found'));        

        $this->controller->setPageTitle($model->displayName().' '.$orderNo);
        
        if ($model instanceof Order && $serviceManager->checkObjectAccess(user()->getId(),$model->shop,$this->accountAttribute)){
            
            $this->viewFile = 'application.modules.orders.views.merchantPO.view';
            $this->renderPage($model);
        }
        elseif ($serviceManager->checkObjectAccess(user()->getId(),$model,$this->accountAttribute)){
            
            $this->viewFile = $this->_isSearchingPO($model,$orderNo)?$this->viewFilePO:$this->viewFileSO;
            $this->renderPage($model);
        }
        else {
            logError('Unauthorized access', $model->getAttributes());
            throwError403(Sii::t('sii','Unauthorized Access'));
        }
    }       
    
    private function _findModel($type, $orderNo,$modelFilter,$finderMethod)
    {
        try {
            $model = $type::model()->$modelFilter()->$finderMethod($orderNo)->find();
            if ($this->_isSearchingPO($model, $orderNo)){ //actually it is searching for PO
                //no shipping order created, find order table (should be unpaid or cancelled order)
                $model = $this->_findModel('Order',$orderNo,'merchant','orderNo');
                logTrace(__METHOD__.' ShippingOrder not found, searching into Order table.', $model->getAttributes());
            }
            elseif ($model===null){
                throw new CHttpException(404,Sii::t('sii','Page not found'));
            }
            
            return $model;
        } catch (CException $e) {
            logError($type.'->'.$modelFilter.'()->'.$finderMethod.'(\''.$orderNo.'\') error',array(),false);
            throwError404(Sii::t('sii','The requested page does not exist'));
        }
    }
    
    private function _isSearchingPO($model,$orderNo)
    {
        return $model==null || ($model instanceof ShippingOrder && $model->order_no==$orderNo);
    }
}
