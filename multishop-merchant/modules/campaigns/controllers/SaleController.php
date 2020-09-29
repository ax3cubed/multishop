<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('campaigns.controllers.CampaignBaseController');
/**
 * Description of SaleController
 *
 * @author kwlok
 */
class SaleController extends CampaignBaseController 
{
    protected $formType = 'CampaignSaleForm';   
    
    public function init()
    {
        parent::init();
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::CAMPAIGN_SALE_IMAGE; 
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->breadcrumbsModuleName = Sii::t('sii','Campaigns');        
        $this->showBreadcrumbsController = true;
        $this->breadcrumbsControllerName = CampaignSale::model()->displayName(Helper::PLURAL);        
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'CampaignSale';
        $this->route = 'campaigns/sale/index';
        $this->viewName = Sii::t('sii','Sale Campaigns');
        $this->sortAttribute = 'update_time';
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'CampaignSaleFilterForm';
        $this->filterFormHomeUrl = url('campaigns/sale');
        //-----------------
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            $this->ckeditorImageUploadAction,
        ]);
        //-----------------//
    }  
    
    protected function getSectionsData($model,$form=false) 
    {
        $sections = new CList();
        if (!$form){
            //section 1: Description
            $sections->add(array('id'=>'description',
                                 'name'=>$model->getAttributeLabel('description'),
                                 'heading'=>true,
                                 'html'=>$model->languageForm->renderForm($this,Helper::READONLY,array('description'),true)));
        } 
        else {
            //section 1: Description
            $sections->add(array('id'=>'description',
                                 'name'=>$model->getAttributeLabel('description'),
                                 'heading'=>true,
                                 'viewFile'=>'_form_description','viewData'=>array('model'=>$model)));
        }
        return $sections->toArray();
    }    
    
}
