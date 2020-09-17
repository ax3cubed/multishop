<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of OrderControllerTrait
 *
 * @author kwlok
 */
trait OrderControllerTrait 
{
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        if ($this->modelType=='ShippingOrder')
            return $this->getScopeFilters_SO();
        else
            return $this->getScopeFilters_PO();
    }    
    /**
     * Scope filters for purchase order controller
     * @return array
     */
    protected function getScopeFilters_PO()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter(array('code'=>'all','text'=>Sii::t('sii','All')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        //unpaid here is a grouping
        $filters->add('unpaid',Helper::htmlIndexFilter(array('code'=>'unpaid','text'=>Sii::t('sii','Unpaid')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        $excludes = array_merge(array(Process::DEFERRED));//deferred status is grouped into "unpaid" - refer to workflowable
        $keys =  array_values(WorkflowManager::getAllEndProcesses(SActiveRecord::restoreTablename($this->modelType),$excludes));
        foreach ($keys as $key) {
            //logTrace(__METHOD__.' '.Helper::phpSafe($key).' , ');
            $filters->add(Helper::phpSafe($key),Helper::htmlIndexFilter(array('code'=>$key,'text'=>Process::getDisplayText(ucwords($key))),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        }
        return $filters->toArray();
    }        
    /**
     * Scope filters for shipping order controller
     */
    protected function getScopeFilters_SO()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter(array('code'=>'all','text'=>Sii::t('sii','All')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        $keys =  array_values(WorkflowManager::getAllEndProcesses(SActiveRecord::restoreTablename($this->modelType)));
        foreach ($keys as $key) {
            $filters->add(Helper::phpSafe($key),Helper::htmlIndexFilter(array('code'=>$key,'text'=>Process::getDisplayText(ucwords($key))),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        }
        return $filters->toArray();
    }    
    /**
     * OVERRIDE METHOD
     * Return the array of scope description
     * 
     * @see SPageIndexAction
     * @return array
     */
    public function getScopeDescription($scope)
    {
        if ($this->modelType=='ShippingOrder')
            return $this->getScopeDesc_SO($scope);
        else
            return $this->getScopeDesc_PO($scope);
    }        
    /**
     * Return the array of scope description for purchase order for merchant
     */
    protected function getScopeDesc_PO($scope)
    {
        switch ($scope) {
            case 'all':
                return Sii::t('sii','This lists all your purchase orders. A purchase order can contain multiple shipping orders.');
            case 'paid':
                return Sii::t('sii','Paid purchase orders are orders that customers have made payment but pending your verification to confirm the order.');
            case 'unpaid':
                return Sii::t('sii','Unpaid orders are orders that customers have chosen offline payment method for and yet to make payment.');
            case 'ordered':
                return Sii::t('sii','These are orders with payment made or verified, but pending fulfillment.');
            case 'rejected':
                return Sii::t('sii','Rejected orders are orders that had failed payment verification.');
            case 'fulfilled':
                return Sii::t('sii','These are orders that have been fully fulfilled and all their purchased items are shipped.');
            case 'partial_fulfilled':
                return Sii::t('sii','These are orders that have been partially fulfilled and there are pending purchased items to be shipped or in other status.');
            case 'cancelled':
                return Sii::t('sii','Cancelled orders are orders that customers had changed their mind for the purchase for any reasons.');
            case 'refunded':
                return Sii::t('sii','Refunded orders are orders that you have refunded customers when you cancelled orders with payment made.');
            default:
                return null;
        }        
    }
    /**
     * Return the array of scope description for shipping order
     */
    protected function getScopeDesc_SO($scope)
    {
        switch ($scope) {
            case 'all':
                return Sii::t('sii','All shipping orders are processed on per shipping basis. If customer purchases multiple products by choosing different shippings in one purchase order, a purchase order can contain multiple shipping orders. ');
            case 'rejected':
                return Sii::t('sii','Rejected orders are orders that had failed payment verification.');
            case 'fulfilled':
                return Sii::t('sii','Fulfilled orders are orders that you had shipped the purchased products to customers.');
            case 'partial_fulfilled':
                return Sii::t('sii','These are orders that have been partially fulfilled and there are pending purchased items to be shipped or in other status.');
            case 'cancelled':
                return Sii::t('sii','Cancelled orders are orders that you do not want to process for any reasons.');
            case 'refunded':
                return Sii::t('sii','Refunded orders are orders that you have refunded customers when you cancelled orders with payment made.');
            default:
                return null;
        }
    }          
    
}

