<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("campaigns.behaviors.CampaignBaseModelBehavior");
/**
 * Description of CampaignBaseForm
 *
 * @author kwlok
 */
class CampaignBaseForm extends LanguageForm 
{
    /**
     * Controlled attributes
     * @see LanguageForm
     */    
    protected $persistentAttributes = array('start_date','end_date','offer_type');
    /*
     * Base local attributes
     */
    public $start_date;
    public $end_date;
    public $status;
    public $create_time;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'campaignbasemodel' => array(
                'class'=>'common.modules.campaigns.behaviors.CampaignBaseModelBehavior',
            ),  
        ));
    }          
    /**
     * @return locale attributes
     */
    public function localeAttributes() 
    {   
        return array();//locale attributes
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
            array('start_date, end_date', 'required'),
            array('start_date', 'compare','compareAttribute'=>'end_date','operator'=>'<','message'=>Sii::t('sii','Start Date must be smaller than End Date')),
            array('status', 'length', 'max'=>10),
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
