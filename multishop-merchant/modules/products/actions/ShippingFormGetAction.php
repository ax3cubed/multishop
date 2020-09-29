<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.schildform.actions.ChildFormGetAction');
/**
 * Description of ShippingFormGetAction
 *
 * @author kwlok
 */
class ShippingFormGetAction extends ChildFormGetAction 
{
    /**
     * Init
     */
    public function init( ) 
    {
        parent::init();
    }
    /**
     * Get shipping form and add session
     * @param integer $type the type of attribute to get
     */
    public function run() 
    {
        if (isset($this->mandatoryGetParam)) {
            if (!isset($_GET[$this->mandatoryGetParam]) && !isset($_GET[$this->formKeyStateVariable])) 
                throwError403(Sii::t('sii','Unauthorized Access'));        
        }
        
        $ids = explode(',', $_GET[$this->mandatoryGetParam]);
        $keepList = new CList();
        $form = '';//initial form
        foreach ($ids as $id) {
            $shipping = $this->_retrieveShippingForm($_GET[$this->formKeyStateVariable],$id);
            $keepList->add($shipping);
            $form = $form.$this->getController()->renderPartial($this->formView,array('form'=>$shipping),true);
        }

        SActiveSession::reload($this->stateVariable,$keepList);
        header('Content-type: application/json');
        echo CJSON::encode(array(
            'status'=>'success',
            'form'=>$form,
        ));
        Yii::app()->end();      
        
    }    
    
    private function _retrieveShippingForm($owner_id,$shipping_id) 
    {
        $found=false;
        foreach (SActiveSession::get($this->stateVariable) as $key => $sessionShipping){
            if ($sessionShipping->shipping_id==$shipping_id){
                $shipping = $sessionShipping; 
                logTrace(__METHOD__.' _mergeWithSessionShippings',$shipping->getAttributes());
                $found=true;
                break; 
            }
        }
        if(!$found){
            $shipping = new $this->formModel($owner_id);
            $shipping->id = $this->getTempId($shipping_id);
            $shipping->shipping_id = $shipping_id;
        }
        return $shipping;
    }    
    
}