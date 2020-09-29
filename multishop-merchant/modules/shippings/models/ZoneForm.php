<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.shippings.behaviors.ShippingBaseBehavior");
/**
 * Description of ZoneForm
 *
 * @author kwlok
 */
class ZoneForm extends LanguageForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'Zone';
    /*
     * Local attributes
     */
    public $name;
    public $country;
    public $state;
    public $city;
    public $postcode;
    public $create_time;    
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'shippingbehavior' => array(
                'class'=>'ShippingBaseBehavior',
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
                'inputHtmlOptions'=>array('size'=>60,'maxlength'=>100),
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
            array('account_id, shop_id, name, country', 'required'),
            array('account_id, shop_id', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>100),
            array('state, city', 'length', 'max'=>100),
            array('country', 'length', 'max'=>200),
            array('postal', 'length', 'max'=>20),
        ));
    } 
    
}
