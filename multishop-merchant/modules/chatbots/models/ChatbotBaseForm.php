<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ChatbotBaseForm
 *
 * @author kwlok
 */
abstract class ChatbotBaseForm extends SFormModel 
{
    public $clientId;
    protected $id;//the form id
    private $_m;//chatbot model instance
    /**
     * Constructor.
     */
    public function __construct($clientId,$scenario='')
    {
        $this->clientId = $clientId;
        //init attributes values if any
        foreach ($this->model->getSettings() as $key => $value) {
            if (property_exists($this,$key))
                $this->$key = $value;
        }
        parent::__construct($scenario);
    }    
    /**
     * Validation rules 
     * @return array validation rules
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['clientId', 'required'],
        ]);
    }

    public function displayName() 
    {
        return Sii::t('sii','Chatbot');
    }
    /**
     * Return chatbot model
     * @return Chatbot
     */
    public function getModel() 
    {
        if (!isset($this->_m)){
            $this->_m = Chatbot::model()->locateClient($this->clientId)->find();
        }
        return $this->_m;
    }    
    /**
     * Return chatbot
     * @return Chatbot
     */
    public function getChatbot() 
    {
        return $this->model;
    }    
    /**
     * Return chatbot provider
     * @return Chatbot
     */
    public function getProvider() 
    {
        return $this->model->provider;
    }    
    
    public function getIsChatbotVerified()
    {
        return $this->model->isVerified;
    }

    public function renderForm()
    {
        Yii::app()->controller->registerCssFile('chatbots.assets.css','chatbots.css');
        Yii::app()->controller->registerScriptFile('chatbots.assets.js','chatbots.js');
        
        bootstrap()->beginModal([
            'id'=> $this->formId,
            'header' => $this->renderFormHeader(true),
            'footer' => $this->renderFormFooter(true),
            'toggleButton' => [
                'label' => $this->displayName(),
                'class' => 'ui-button extra-button '.($this->isChatbotVerified?'':' disabled-button'),
                'disabled'=>!$this->isChatbotVerified,
            ],
            'options'=>[
                'class'=>'chatbot-modal',
            ],
        ]);

        $this->renderFormBody();

        bootstrap()->endModal($this->formId);

        $this->renderFormScript();
    }
    
    protected function renderFormHeader($return=false)
    {
        $output = $this->renderFormViewFile('_form_header', ['chatbot'=>$this->chatbotName], $return);
        if ($return)
            return $output;
    }
    
    protected function renderFormBody($return=false)
    {
        $output = $this->renderFormViewFile('_form_body', ['model'=>$this], $return);
        if ($return)
            return $output;
    }

    protected function renderFormFooter($return=false)
    {
        $output = $this->renderFormViewFile('_form_footer', [], $return);
        if ($return)
            return $output;
    }
    
    protected function renderFormScript()
    {
        $this->renderFormViewFile('_form_script', ['formId'=>$this->formId]);
    }
    
    protected function renderFormViewFile($viewName,$params=[],$return=false)
    {
        if (($alias = $this->existsViewFile($viewName))!=false){
            if ($return)
                return Yii::app()->controller->renderPartial($alias,$params,true);
            else
                Yii::app()->controller->renderPartial($alias,$params);
        }
        else
            return null;
    }
    
    protected function getFormId()
    {
        return $this->provider.'_'.$this->id.'_modal';
    }
    
    protected function existsViewFile($viewName)
    {
        $alias = 'chatbots.views.'.$this->id.'.'.$this->provider.'.'.$viewName;
        $viewFile = Yii::getPathOfAlias($alias);    
        if (file_exists($viewFile.'.php'))
            return $alias;
        else
            return false;
    }
    
    protected function getChatbotName()
    {
        switch ($this->provider) {
            case Chatbot::MESSENGER:
                return Sii::t('sii','Messenger');
            default:
                return Sii::t('sii','Chatbot');
        }
    }
}
