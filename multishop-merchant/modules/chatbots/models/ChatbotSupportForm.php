<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('chatbots.models.ChatbotBaseForm');
Yii::import('common.modules.chatbots.payloads.LiveChatAgentPayload');
Yii::import('common.modules.chatbots.models.ChatbotSupport');
/**
 * Description of ChatbotSupportForm
 * @todo Support a team of agents, and on job duty and rotation
 * 
 * @author kwlok
 */
class ChatbotSupportForm extends ChatbotBaseForm 
{
    protected $id = 'support';//the id
    public $support = 0;//default 1=yes (0=No)
    public $agentId;//to be assigned, @see ChatbotSupport::prepareAgentData
    public $agentAccountId;//to be assigned, @see ChatbotSupport::prepareAgentData
    public $agentName;
    public $workingDays = [0=>0,1=>1,2=>1,3=>1,4=>1,5=>1,6=>0];//selection of week days, seperated by comma
    public $openTime = '0800';//support open time, format: H:i
    public $closeTime = '1830';//support close time, format: H:i
    /**
     * Validation rules 
     * @return array validation rules
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['support, agentName, workingDays,openTime,closeTime', 'required'],
            ['support', 'boolean'],
            ['workingDays', 'ruleWorkingDays'],
            ['agentId', 'length', 'min'=>1,'max'=>50],//normally this shall follow chatbot side user id; here put it to sufficient length and also to have a validation
            ['agentAccountId', 'numerical', 'integerOnly'=>true],
            ['agentId', 'length', 'min'=>1,'max'=>50],//normally this shall follow chatbot side user id; here put it to sufficient length and also to have a validation
            ['agentName', 'length', 'min'=>1,'max'=>20],
            //only digits
            ['openTime,closeTime', 'match', 'pattern'=>'/^[0-9]+$/', 'message'=>Sii::t('sii','Working hours accepts only digits.')],
            ['openTime,closeTime', 'length', 'min'=>4,'max'=>4],
            ['openTime', 'compare','compareAttribute'=>'closeTime','operator'=>'<','message'=>Sii::t('sii','Open Time must be smaller than Close Time')],
        ]);
    }
    
    public function ruleWorkingDays($attribute,$params)
    {
        foreach ($this->workingDays as $key => $value) {
            if (!in_array($key, array_keys(ChatbotSupport::getWorkingDaysArray())))
                $this->addError('workingDays', Sii::t('sii','Invalid working days: {day}',['{day}'=>$key]));
        }
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeToolTips(),[
            'agentId'=>Sii::t('sii','Agent Id'),
            'agentName'=>Sii::t('sii','Agent Name'),
        ]);
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge(parent::attributeToolTips(),[
            'support'=>Sii::t('sii','Enable live chat online support'),
            'agentsSignupTip'=>Sii::t('sii','Click below to register as live chat agent'),
            'agentAssignment'=>Sii::t('sii','Agent Assignment'),
            'agentName'=>Sii::t('sii','Enter agent name here'),
            'agentNameTip'=>Sii::t('sii','Give agent a name'),
            'workingDays'=>Sii::t('sii','Set your online support working days.'),
            'workingHours'=>Sii::t('sii','Working Hours'),
            'workingHoursTip'=>Sii::t('sii','24 hours format HHMM, e.g. 0915, 2359'),
            'openTime'=>Sii::t('sii','Opening hour'),
            'closeTime'=>Sii::t('sii','Closing hour'),
        ]);
    }

    public function displayName() 
    {
        return Sii::t('sii','Online Support Settings');
    }
    
    public function getHasAgent()
    {
        return $this->agentId!=null;
    }
    
    public function getPayload($account)
    {
        $payload = new LiveChatAgentPayload($this->chatbot->owner->id,$account);
        return $payload->toString();
    }
    
    public function getMessengerCallbackScript()
    {
        $message = Sii::t('sii','Refresh page to get the new Agent Id');
        $js = <<<EOJS
$('.registered-agent').html('$message');
$('.registered-agent').css({'color':'green'});
EOJS;
        return $js;
    }
}
