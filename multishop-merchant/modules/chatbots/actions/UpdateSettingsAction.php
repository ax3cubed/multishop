<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of UpdateSettingsAction
 *
 * @author kwlok
 */
class UpdateSettingsAction extends CAction 
{
    public $formModel;
    /**
     * Run action
     */
    public function run() 
    {
        logInfo(__METHOD__.' $_POST',$_POST);
        
        if (isset($_POST[$this->formModel]['clientId'])){
            $clientId = $_POST[$this->formModel]['clientId'];
            $attributes = $_POST[$this->formModel];
            unset($attributes['clientId']);//client id is not setting attributes
            $form = new $this->formModel($clientId);
            $form->attributes = $attributes;
            logTrace(__METHOD__.' form attributes',$form->attributes);

            try {
                   
                if (!$form->validate()){
                    throw new CException(Helper::htmlErrors($form->errors));
                }
                
                $chatbot =  $this->controller->module->serviceManager->updateSettings(user()->getId(),$form->model,$attributes);
                $message = Sii::t('sii','Settings saved successfully.');
                $status = 'success';
                
            } catch (Exception $ex) {
                logError(__METHOD__.' error',$ex->getTraceAsString());
                $message = $ex->getMessage();
                $status = 'error';
            }            
            
            unset($_POST);    
            
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'flash'=>$this->controller->getFlashAsString($status,null,$message),
            ));
            Yii::app()->end();
        }
        
        throwError403(Sii::t('sii','Unauthorized access'));    }
}
