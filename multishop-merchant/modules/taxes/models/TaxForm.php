<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.taxes.behaviors.TaxBehavior');
/**
 * Description of TaxForm
 *
 * @author kwlok
 */
class TaxForm extends LanguageForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'Tax';
    /*
     * Local attributes
     */
    public $name;
    public $rate;
    public $status;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'taxbehavior' => array(
                'class'=>'common.modules.taxes.behaviors.TaxBehavior',
            ),
        ));
    }    
    /**
     * @return array Definitions of form attributes that requires multi languages
     */       
    public function localeAttributes()
    {
        return array(
            'name'=>array(
                'required'=>true,
                'inputType'=>'textField',
                'inputHtmlOptions'=>array('size'=>60,'maxlength'=>50),
            ),
        );
    }     
    /**
     * Validation rules for locale attributes
     * 
     * Note: that all different locale values of one attributes are to be stored in db table column
     * Hence, model attribute (table column) wil have separate validation rules following underlying table definition
     * 
     * @return array validation rules for locale attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(),array(
            array('account_id, shop_id, name, rate', 'required'),
            array('account_id, shop_id', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>50),
            array('rate', 'length', 'max'=>10),
            // validate field 'type' to make sure correct rate is entered
            array('rate', 'type', 'type'=>'float','allowEmpty'=>false),
        ));
    }     
    /**
     * Return status text
     * @param type $color
     * @return type
     */
    public function getStatusText($color=true)
    {
        return $this->modelInstance->getStatusText($color);
    }    
}
