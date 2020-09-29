<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.spagefilter.models.SPageFilterForm');
/**
 * Description of CampaignBgaFilterForm
 * 
 * @author kwlok
 */
class CampaignBgaFilterForm extends CampaignFilterForm 
{
    public $type = 'CampaignBga';   
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'campaign'=>Sii::t('sii','BGA Campaign Name'),
        ]);
    }        
}
