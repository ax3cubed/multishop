<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of BillingForm
 *
 * @author kwlok
 */
class BillingForm extends SFormModel 
{
    public $id;//billing id
    public $email;
    public $billed_to;
    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['id, email', 'required'],
            ['email', 'email'],
            ['billed_to, email', 'length', 'max'=>100],
        ];
    }    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'email' => Sii::t('sii','Email'),
            'billed_to' => Sii::t('sii','Billed To'),
        ];
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return [
            'email' => Sii::t('sii','This email will receive the receipt.'),
            'billed_to' => Sii::t('sii','This will be the name appeared on the receipt. It can be organization\'s name or individual name.'),
        ];
    }  

    public function displayName() 
    {
        return Sii::t('sii','Billing');
    }
}
