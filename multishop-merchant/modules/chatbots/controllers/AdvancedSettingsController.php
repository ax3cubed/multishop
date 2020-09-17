<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of AdvancedSettingsController
 *
 * @author kwlok
 */
class AdvancedSettingsController extends AuthenticatedController
{
    protected $formModel = 'ChatbotAdvancedForm';
    /**
     * Init
     */
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Chatbot Advanced Settings');
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'update'=>[
                'class'=>'chatbots.actions.UpdateSettingsAction',
                'formModel'=>$this->formModel,
            ],                   
        ]);
    }     
    /**
     * Action to send greeting text to Facebook
     * @throws CException
     */
    public function actionSendGreetingText()
    {
        if (!isset($_POST[$this->formModel]['greetingText'])){
            throwError400(Sii::t('sii','Bad request'));
        }
        
        $this->sendThreadSetting('sendGreetingText', Sii::t('sii','Greeting text saved and sent to Messenger successfully.'),[
            'greetingText' => $_POST[$this->formModel]['greetingText'],
        ]);
    }
    /**
     * Action to send get started button to Facebook
     * @throws CException
     */
    public function actionSendGetStartedButton()
    {
        $this->sendThreadSetting('sendGetStartedButton', Sii::t('sii','Get Started Button sent to Messenger successfully.'));
    }    
    /**
     * Action to send persistent menu to Facebook
     * @throws CException
     */
    public function actionSendPersistentMenu()
    {
        $this->sendThreadSetting('sendPersistentMenu', Sii::t('sii','Persistent Menu sent to Messenger successfully.'));
    }     
    /**
     * A internal wrapper to send thread setting to Facebook Messenger
     * @throws CException
     */
    protected function sendThreadSetting($service,$successMessage,$settingValues=[])
    {
        logInfo(__METHOD__,$_POST);
        
        if (isset($_POST[$this->formModel]['clientId'])){

            $form = new $this->formModel($_POST[$this->formModel]['clientId']);
            foreach ($settingValues as $key => $value) {
                $form->$key = $value;
            }
            
            try {

                if ($form->chatbot->provider==Chatbot::MESSENGER){
                    if ($service=='sendGreetingText')
                        $chatbot =  $this->module->serviceManager->{$service}(user()->getId(),$form->chatbot,$form->greetingText);
                    else
                        $chatbot =  $this->module->serviceManager->{$service}(user()->getId(),$form->chatbot);
                        
                    $lastSentField = lcfirst(substr($service,4));
                    $lastSentTime = $chatbot->getLastSentTime($lastSentField,true);
                    $message = $successMessage;
                    $status = 'success';
                }
                else
                    throw new CException('Chatbot is not Messenger.');
                
            } catch (Exception $ex) {
                logError(__METHOD__.' error',$ex->getTraceAsString());
                $message = $ex->getMessage();
                $status = 'error';
            }            
            
            unset($_POST);    
            
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'flash'=>$this->getFlashAsString($status,null,$message),
                'last_sent_field'=>isset($lastSentField)?$lastSentField:null,
                'last_sent_time'=>isset($lastSentTime)?$lastSentTime:null,
            ));
            Yii::app()->end();
        }
        
        throwError403(Sii::t('sii','Unauthorized access'));
        
    }     
}
