<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CampaignSaleForm
 *
 * @author kwlok
 */
class CampaignSaleForm extends CampaignBaseForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'CampaignSale';
    /*
     * Local attributes
     */
    public $name;
    public $sale_type;
    public $sale_value;
    public $offer_type;
    public $offer_value;
    public $image;
    public $description;
    /**
     * @return array Definitions of form attributes that requires multi languages
     */       
    public function localeAttributes()
    {
        return [
            'name'=>[
                'required'=>true,
                'inputType'=>'textField',
                'inputHtmlOptions'=>['size'=>60,'maxlength'=>100],
            ],
            'description'=>[
                'required'=>false,
                'purify'=>true,
                'label'=>false,
                'inputType'=>'textArea',
                'inputHtmlOptions'=>['size'=>60,'rows'=>5],
                'ckeditor'=>[
                    'imageupload'=>true,
                    'js'=>'campaignckeditor.js',
                ],
            ],
        ];
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
        return array_merge(parent::rules(),[
            ['name, sale_value, offer_value, sale_type, offer_type', 'required'],
            ['name', 'length', 'max'=>100],
            ['offer_value', 'length', 'max'=>8],
            ['sale_value', 'length', 'max'=>8],
            ['sale_type, offer_type', 'length', 'max'=>1],
            ['sale_value', 'ruleSale','min'=>1,'max'=>100],
            ['offer_value', 'ruleOfferAmount','min'=>1,'max'=>100],
            ['image', 'numerical', 'integerOnly'=>true],
            ['image', 'safe'],
            ['description', 'safe'],
        ]);
    } 
    /**
     * @see CampaignSale::ruleSale()
     */
    public function ruleSale($attribute,$params)
    {
        $this->modelInstance->sale_type = $this->sale_type;//these values are required for vaidation
        $this->modelInstance->sale_value = $this->sale_value;
        $this->modelInstance->offer_value = $this->offer_value;
        $this->modelInstance->offer_type = $this->offer_type;
        $this->modelInstance->ruleSale($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }    
    /**
     * @see CampaignSale::ruleOfferAmount()
     */
    public function ruleOfferAmount($attribute,$params)
    {
        $this->modelInstance->offer_value = $this->offer_value;
        $this->modelInstance->offer_type = $this->offer_type;
        $this->modelInstance->ruleOfferAmount($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    } 

}
