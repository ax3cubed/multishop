<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.spagefilter.models.SPageFilterForm');
/**
 * Description of CampaignPromocodeFilterForm
 * 
 * @author kwlok
 */
class CampaignPromocodeFilterForm extends CampaignFilterForm 
{
    public $type = 'CampaignPromocode';   
    /**
     * Initializes this model.
     */
    public function init()
    {
        parent::init();
        //override setting
        $this->config['campaign']['htmlOptions'] = ['maxlength'=>12,'placeholder'=>Sii::t('sii','Enter any promocode')];
    }      
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['campaign', 'length', 'max'=>12],
        ]);
    }     
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'campaign'=>Sii::t('sii','Promotional Code'),
        ]);
    }        
}
