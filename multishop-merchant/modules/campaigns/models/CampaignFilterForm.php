<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.spagefilter.models.SPageFilterForm');
/**
 * Description of CampaignFilterForm
 * 
 * This form's attributes must match to the search model defined in controller
 * @see SPageIndexController::searchMap
 * @see SPageIndexAction::getSearchModel()
 * @see SPageIndexControllerTrait::assignFilterFormAttributes()
 * 
 * @author kwlok
 */
class CampaignFilterForm extends SPageFilterForm 
{
    CONST STATUS_FLAG = 'CPG;';//campaign status starting string; Refer to Process model
    /*
     * Meta attributes
     */
    public $type;//campaign type
    /**
     * Form fields
     * The sequence of fields will decide its display order
     */
    public $campaign;
    public $date;
    public $start_date;
    public $end_date;
    public $offer_type;
    public $status;
    /**
     * Initializes this model.
     */
    public function init()
    {
        $this->config = [
            'campaign'=>[
                'type'=>SPageFilterForm::TYPE_TEXTFIELD,
                'htmlOptions'=>['maxlength'=>100,'placeholder'=>Sii::t('sii','Enter any campaign name')],
            ],
            'date'=>[
                'type'=>SPageFilterForm::TYPE_DATE,
                'ops'=>[
                    SPageFilterForm::OP_EQUAL,
                    SPageFilterForm::OP_NOT_EQUAL,
                    SPageFilterForm::OP_GREATER_THAN,
                    SPageFilterForm::OP_GREATER_THAN_OR_EQUAL,
                    SPageFilterForm::OP_LESS_THAN,
                    SPageFilterForm::OP_LESS_THAN_OR_EQUAL,
                ],
                'htmlOptions'=>['maxlength'=>10,'class'=>'date-field','placeholder'=>Sii::t('sii','yyyy-mm-dd')],
            ],  
            'start_date'=>[
                'type'=>SPageFilterForm::TYPE_DATE,
                'ops'=>[
                    SPageFilterForm::OP_EQUAL,
                    SPageFilterForm::OP_NOT_EQUAL,
                    SPageFilterForm::OP_GREATER_THAN,
                    SPageFilterForm::OP_GREATER_THAN_OR_EQUAL,
                    SPageFilterForm::OP_LESS_THAN,
                    SPageFilterForm::OP_LESS_THAN_OR_EQUAL,
                ],
                'htmlOptions'=>['maxlength'=>10,'class'=>'date-field','placeholder'=>Sii::t('sii','yyyy-mm-dd')],
            ],  
            'end_date'=>[
                'type'=>SPageFilterForm::TYPE_DATE,
                'ops'=>[
                    SPageFilterForm::OP_EQUAL,
                    SPageFilterForm::OP_NOT_EQUAL,
                    SPageFilterForm::OP_GREATER_THAN,
                    SPageFilterForm::OP_GREATER_THAN_OR_EQUAL,
                    SPageFilterForm::OP_LESS_THAN,
                    SPageFilterForm::OP_LESS_THAN_OR_EQUAL,
                ],
                'htmlOptions'=>['maxlength'=>10,'class'=>'date-field','placeholder'=>Sii::t('sii','yyyy-mm-dd')],
            ],  
            'offer_type'=>[
                'type'=>SPageFilterForm::TYPE_DROPDOWNLIST,
                'selectOptions'=>array_merge([''=>Sii::t('sii','Select Offer Type')],$this->getOfferTypes()),
                'htmlOptions'=>['placeholder'=>Sii::t('sii','Select Offer Type')],
            ],
            'status'=>[
                'type'=>SPageFilterForm::TYPE_DROPDOWNLIST,
                'selectOptions'=>array_merge([''=>Sii::t('sii','Select Status')],self::getAllStatus()),
                'htmlOptions'=>['placeholder'=>Sii::t('sii','Select Status')],
            ],
        ];
    }    
    /**
     * Get form attributes (removing all the meta data attributes)
     * @return type
     */    
    public function getFields()
    {
        $fields = parent::getFields();
        unset($fields['type']);
        return $fields;
    }    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['campaign', 'length', 'max'=>100],
            ['status', 'length', 'max'=>20],
            ['offer_type', 'length', 'max'=>1],
            ['status', 'ruleStatus'],
            ['offer_type', 'ruleOfferType'],
        ];
    } 
    /**
     * Verify campaign offer type
     */
    public function ruleOfferType($attribute,$params)
    {
        if (!empty($this->$attribute)){
            if (!in_array($this->$attribute,array_keys($this->getOfferTypes())))
                $this->addError($attribute,Sii::t('sii','Invalid offer type.'));
        }
    }      
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'campaign'=>Sii::t('sii','Campaign or Promotional Code'),
            'date'=>Sii::t('sii','Created At'),
            'start_date'=>Sii::t('sii','Start Date'),
            'end_date'=>Sii::t('sii','End Date'),
            'offer_type'=>Sii::t('sii','Offer Type'),
            'status'=>Sii::t('sii','Status'),
        ];
    }    
    
    public function getOfferTypes()
    {
        $model = new $this->type();
        return $model->getOfferTypes();
    }

    public static function getAllStatus($status=[])
    {
        return parent::getAllStatus([
            self::STATUS_ONLINE,
            self::STATUS_OFFLINE,
            self::STATUS_EXPIRED,
        ]);
    }
}
