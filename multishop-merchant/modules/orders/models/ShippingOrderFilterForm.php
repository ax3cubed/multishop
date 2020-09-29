<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.orders.models.OrderFilterForm');
/**
 * Description of OrderFilterForm
 * 
 * This form's attributes must match to the search model defined in controller
 * @see SPageIndexController::searchMap
 * @see SPageIndexAction::getSearchModel()
 * @see SPageIndexControllerTrait::assignFilterFormAttributes()
 * 
 * @author kwlok
 */
class ShippingOrderFilterForm  extends OrderFilterForm 
{
    /**
     * Form fields
     * The sequence of fields will decide its display order
     */
    public $shipping_no;
    /**
     * Initializes this model.
     */
    public function init()
    {
        parent::init();//load parent config
        $this->config = array_merge($this->config,[
            'shipping_no'=>[
                'type'=>SPageFilterForm::TYPE_TEXTFIELD,
                'htmlOptions'=>['maxlength'=>20,'placeholder'=>Sii::t('sii','Enter any shipping order no')],
            ],
        ]);
    }    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['shipping_no', 'length', 'max'=>20],   
        ]);
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'shipping_no'=>Sii::t('sii','Shipping Order No'),
        ]);
    }    
}
