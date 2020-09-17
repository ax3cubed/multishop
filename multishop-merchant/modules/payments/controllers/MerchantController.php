<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of MerchantController
 *
 * @author kwlok
 */
class MerchantController extends SPageIndexController 
{
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Payment';
        $this->route = 'account/payments';
        $this->viewName = Sii::t('sii','Payments');
        $this->enableViewOptions = true;
        $this->enableSearch = false;
        //-----------------//
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'view'=>[
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'finderMethod'=>'paymentNo',
            ],                    
        ]);
    } 
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter('All', false));
        return $filters->toArray();
    }      
    
    public function getReferenceUrl($referenceNo)
    {
        return Receipt::viewUrl($referenceNo);
    }
}