<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SettingsController
 *
 * @author kwlok
 */
class SettingsController extends BillingBaseController 
{
    protected $model = 'BillingForm';
    /**
     * Index page
     */
    public function actionIndex()
    {
        $this->pageTitle = Sii::t('sii','Billing Settings');
        $model = new BillingForm();
        $billing = $this->billingRecord;
        
        if (isset($_POST[$this->model])){
            $billing->attributes = $_POST[$this->model];

            try {
                $this->module->serviceManager->update(user()->getId(),$billing);
                user()->setFlash($this->model,[
                    'message'=>Sii::t('sii','{model} is saved successfully.',['{model}'=>$billing->displayName()]),
                    'type'=>'success',
                    'title'=>Sii::t('sii','{model} Update',['{model}'=>$billing->displayName()]),
                ]);
                
                unset($_POST);
                
            } catch (CException $e) {
                logError(__METHOD__.' '.$this->model.' update error',$billing->getErrors());
                user()->setFlash($this->model,[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','{model} Error',['{model}'=>$billing->displayName()]),
                ]);
            }            
        }
        
        if ($billing!=null){
            $model->email = $billing->email;
            $model->billed_to = $billing->billed_to;
        }        
        $this->render('index',['model'=>$model]);
    }
    
    protected function getBillingRecord()
    {
        return Billing::model()->mine()->find();
    }    
}
