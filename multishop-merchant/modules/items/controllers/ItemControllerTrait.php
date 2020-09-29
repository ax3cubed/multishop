<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ItemControllerTrait
 *
 * @author kwlok
 */
trait ItemControllerTrait 
{
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter(array('code'=>'all','text'=>Sii::t('sii','All')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        //unpaid here is a grouping
        $filters->add('unpaid',Helper::htmlIndexFilter(array('code'=>'unpaid','text'=>Sii::t('sii','Unpaid')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        $excludes = array_merge(Item::model()->getPendingProcesses(),Item::model()->getRejectedProcesses(),Item::model()->getReceivedProcesses(),array(Process::DEFERRED));//deferred status is grouped into "unpaid" - refer to workflowable
        $keys =  array_values(WorkflowManager::getAllEndProcesses(SActiveRecord::restoreTablename($this->modelType),$excludes));
        foreach ($keys as $key) {
            //logTrace(__METHOD__.' '.$key.' , ');
            $filters->add($key,Helper::htmlIndexFilter(array('code'=>$key,'text'=>Process::getDisplayText(ucfirst($key))),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        }
        $filters->add('received',Helper::htmlIndexFilter(array('code'=>'received','text'=>Sii::t('sii','Received')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));//received grouping
        $filters->add('rejected',Helper::htmlIndexFilter(array('code'=>'rejected','text'=>Sii::t('sii','Rejected')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));//rejected grouping
        $filters->add('pending',Helper::htmlIndexFilter(array('code'=>'pending','text'=>Sii::t('sii','Pending')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));//pending grouping
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
        switch ($scope) {
            case 'all':
                return Sii::t('sii','This lists all the items that you have sold.');
            case 'unpaid':
                return Sii::t('sii','Unpaid items are items that customer have not made payment.');
            case 'paid':
                return Sii::t('sii','Paid items are items that your pending payment verification before shipping.');
            case 'ordered':
                return Sii::t('sii','These are confirmed purchased items that should be get started to ship them.');
            case 'shipped':
                return Sii::t('sii','These are items that already shipped.');
            case 'rejected':
                return Sii::t('sii','These are items that rejected due to unsuccessful payment verification.');
            case 'cancelled':
                return Sii::t('sii','These are items that either cancelled by customer or yourselves. You can check the reason of cancellation.');
            case 'returned':
                return Sii::t('sii','Returned items are items that you had accepted customers item return requests. Once you accept the return request, refund process will kick in.');
            case 'received':
                return Sii::t('sii','Received items are items that already acknowledged by customer.');
            case 'refunded':
                return Sii::t('sii','Refunded items are items that had been cancelled, returned or refunded.');
            case 'pending':
                return Sii::t('sii','Pending items are items that currently being processed.');
            default:
                return null;
        }
    }         
}

