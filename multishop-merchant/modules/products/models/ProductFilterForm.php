<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.spagefilter.models.SPageFilterForm');
/**
 * Description of ProductFilterForm
 * 
 * This form's attributes must match to the search model defined in controller
 * @see SPageIndexController::searchMap
 * @see SPageIndexAction::getSearchModel()
 * @see SPageIndexControllerTrait::assignFilterFormAttributes()
 * 
 * @author kwlok
 */
class ProductFilterForm extends SPageFilterForm 
{
    CONST STATUS_FLAG = 'PRD;';//product status starting string; Refer to Process model
    /**
     * Form fields
     * The sequence of fields will decide its display order
     */
    public $product;
    public $price;
    public $date;
    public $shipping;
    public $status;
    /**
     * Initializes this model.
     */
    public function init()
    {
        $this->config = [
            'product'=>[
                'type'=>SPageFilterForm::TYPE_TEXTFIELD,
                'htmlOptions'=>['maxlength'=>100,'placeholder'=>Sii::t('sii','Enter any product name')],
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
            'price'=>[
                'type'=>SPageFilterForm::TYPE_TEXTFIELD,
                'ops'=>[
                    SPageFilterForm::OP_EQUAL,
                    SPageFilterForm::OP_NOT_EQUAL,
                    SPageFilterForm::OP_GREATER_THAN,
                    SPageFilterForm::OP_GREATER_THAN_OR_EQUAL,
                    SPageFilterForm::OP_LESS_THAN,
                    SPageFilterForm::OP_LESS_THAN_OR_EQUAL,
                ],
                'htmlOptions'=>['maxlength'=>10,'class'=>'numeric-field','placeholder'=>Sii::t('sii','Numeric only')],
            ], 
            'shipping'=>[
                'type'=>SPageFilterForm::TYPE_TEXTFIELD,
                'htmlOptions'=>['maxlength'=>50,'placeholder'=>Sii::t('sii','Enter any shipping name')],
            ],      
            'status'=>[
                'type'=>SPageFilterForm::TYPE_DROPDOWNLIST,
                'selectOptions'=>array_merge([''=>Sii::t('sii','Select Status')],self::getAllStatus()),
                'htmlOptions'=>['placeholder'=>Sii::t('sii','Select Status')],
            ],            
        ];
    }    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['product, shipping', 'length', 'max'=>100],
            ['price', 'length', 'max'=>10],   
            ['price', 'type', 'type'=>'float'],
            ['status', 'length', 'max'=>20],
            ['status', 'ruleStatus'],
        ];
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'product'=>Sii::t('sii','Product Name'),
            'date'=>Sii::t('sii','Created At'),
            'price'=>Sii::t('sii','Unit Price'),
            'shipping'=>Sii::t('sii','Shipping Option'),
            'status'=>Sii::t('sii','Status'),
        ];
    }    
    
    public static function getAllStatus($status=[])
    {
        return parent::getAllStatus([
            self::STATUS_ONLINE,
            self::STATUS_OFFLINE,
        ]);
    }
    
}
