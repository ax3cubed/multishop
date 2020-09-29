<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("campaigns.behaviors.CampaignBgaModelBehavior");
/**
 * Description of CampaignBgaForm
 *
 * @author kwlok
 */
class CampaignBgaForm extends LanguageParentForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'CampaignBga';
    protected $persistentAttributes = ['id','shop_id','start_date','end_date','buy_x','buy_x_qty','get_y','get_y_qty','at_offer','offer_type','shippings'];
    protected $childFormClass = 'CampaignShippingForm';
    protected $childFormAttribute = 'shippings';
    protected $childFormModelAttributes = ['shipping_id','campaign_id','surcharge'];
    /*
     * Local attributes
     */
    public $name;
    public $image;
    public $description;
    public $buy_x;
    public $buy_x_qty;
    public $get_y;
    public $get_y_qty;
    public $at_offer;
    public $offer_type;    
    public $start_date;
    public $end_date;
    public $status;
    public $create_time;
    public $shippings=[];  
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'campaignbgamodel' => [
                'class'=>'common.modules.campaigns.behaviors.CampaignBgaModelBehavior',
            ],  
        ]);
    }      
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
            ['start_date, end_date', 'required'],
            ['start_date', 'compare','compareAttribute'=>'end_date','operator'=>'<','message'=>Sii::t('sii','Start Date must be smaller than End Date')],
            ['status', 'length', 'max'=>10],
            ['name, buy_x, offer_type', 'required'],
            ['image, buy_x, buy_x_qty, get_y, get_y_qty', 'numerical', 'integerOnly'=>true],
            ['name', 'length', 'max'=>100],
            ['at_offer', 'length', 'max'=>8],
            ['offer_type', 'length', 'max'=>1],
            ['status', 'length', 'max'=>10],
            ['image', 'safe'],
            ['description', 'safe'],
            ['buy_x', 'ruleOfferProduct'],             
            ['at_offer', 'ruleAtOffer','min'=>1,'max'=>100],
            //business rules
            ['shippings', 'ruleShippings'],
        ]);
    }     
    /**
     * @see CampaignBga::ruleOfferProduct()
     */
    public function ruleOfferProduct($attribute,$params)
    {
        //these values are required for vaidation
        $this->modelInstance->attributes = $this->getAttributes(['id','shop_id','buy_x','buy_x_qty','get_y','get_y_qty','at_offer','offer_type']);
        $this->modelInstance->ruleOfferProduct($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    } 
    /**
     * @see CampaignBga::ruleAtOffer()
     */
    public function ruleAtOffer($attribute,$params)
    {
        //these values are required for vaidation
        $this->modelInstance->attributes = $this->getAttributes(['id','shop_id','buy_x','buy_x_qty','get_y','get_y_qty','at_offer','offer_type']);
        $this->modelInstance->ruleAtOffer($attribute,$params);
        if ($this->modelInstance->hasErrors())
            $this->addErrors($this->modelInstance->getErrors());
    }     
    /**
     * @see CampaignBga::ruleShippings()
     */
    public function ruleShippings($attribute,$params)
    {
        $previousBase = null;
        foreach ($this->shippings as $shipping) {
            //logTrace(__METHOD__.' '. get_class($shipping).' attribute',$shipping->attributes);
            //validation [1]
            $shipping->validateLocaleAttributes();            
            //validation [2]
            $tier = ShippingTier::model()->findByAttributes(['shipping_id'=>$shipping->shipping_id]);
            if ($tier!=null){
                if ($previousBase===null)
                    $previousBase = $tier->base;
                if ($previousBase != $tier->base){
                    $this->addError('id',Sii::t('sii','Tiered Type Shipping Base must all be the same.'));
                    break;
                }
                $previousBase = $tier->base;
            }
            
            if ($shipping->hasErrors()){
                logTraceDump(__METHOD__.' shipping errors',$shipping->getErrors());
                $this->addErrors($shipping->getErrors());
            }
        }//end for loop
    }  
    /**
     * Validate shippings
     */
    public function validateChildForm() 
    {
        $this->ruleShippings('shippings',[]);
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
    /**
     * @return model attributes to be copied
     */
    public function getModelAttributes()
    {
        $attributes = $this->getAttributes();
        unset($attributes['model']);
        unset($attributes['shippings']);
        return $attributes;
    }  

}
