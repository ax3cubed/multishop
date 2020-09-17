<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.inventories.behaviors.InventoryBehavior");
/**
 * Description of InventoryForm
 *
 * @author kwlok
 */
class InventoryForm extends LanguageForm 
{   
    /*
     * The model that this form is representing
     */
    public $model = 'Inventory';
    /**
     * Local property
     */
    public $obj_type;
    public $obj_id;
    public $sku;
    public $quantity;
    public $available;
    public $hold;
    public $sold;
    public $bad;
    public $adjust;//extra property
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'inventorybehavior' => array(
                'class'=>'InventoryBehavior',
            ),
        ));
    }      
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('adjust', 'required'),
            array('adjust', 'numerical', 'integerOnly'=>true),
        );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $labels = ['adjust'=>Sii::t('sii','Adjust')];
        return array_merge($labels,parent::attributeLabels());
    }
    /**
     * No locale attributes
     * @return type
     */
    public function localeAttributes() 
    {
        return array();
    }

}