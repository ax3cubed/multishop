<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('chatbots.models.ChatbotBaseForm');
/**
 * Description of ChatbotAdvancedForm
 *
 * @author kwlok
 */
class ChatbotAdvancedForm extends ChatbotBaseForm 
{
    protected $id = 'advancedSettings';//the id
    public static $greetingTextLengthLimit = 160;
    public $greetingText;
    /**
     * Validation rules 
     * @return array validation rules
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            array('greetingText', 'length', 'max'=>self::$greetingTextLengthLimit),
        ]);
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge(parent::attributeToolTips(),[
            'greetingText'=>Sii::t('sii','Example: "Hi {{user_first_name}}, welcome to our shop chatbot!". Maximum {length} chars. ',['{length}'=>self::$greetingTextLengthLimit]),
        ]);
    }

    public function displayName() 
    {
        return Sii::t('sii','Advanced Settings');
    }

    public function getLastSentTimeText($field)
    {
        return $this->model->getLastSentTime($field,true);
    }
    
}
