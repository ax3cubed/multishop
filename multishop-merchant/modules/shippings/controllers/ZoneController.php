<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shippings.controllers.ShippingBaseController');
/**
 * Description of ZoneController
 *
 * @author kwlok
 */
class ZoneController extends ShippingBaseController
{
    protected $formType = 'ZoneForm';    
    
    public function init()
    {
        parent::init();
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsController = true;
        $this->breadcrumbsControllerName = Zone::model()->displayName(Helper::PLURAL);        
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Zone';
        $this->route = 'shippings/zone/index';
        $this->viewName = Sii::t('sii','Zones Management');
        $this->sortAttribute = 'update_time';
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            'view'=>array(
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'name',
            ),                    
            'create'=>array(
                'class'=>'common.components.actions.LanguageCreateAction',
                'form'=>$this->formType,
                'createModelMethod'=>'prepareForm',
                'service'=>'createZone',
            ),
            'update'=>array(
                'class'=>'common.components.actions.LanguageUpdateAction',
                'form'=>$this->formType,
                'loadModelMethod'=>'prepareForm',
                'service'=>'updateZone',
            ), 
            'delete'=>array(
                'class'=>'common.components.actions.LanguageDeleteAction',
                'model'=>$this->modelType,
                'service'=>'deleteZone',
            ),
        ));
    }          
    
    public function prepareForm($id=null)
    {
        if (isset($id)){//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes();
        }
        else {
            $form = new $this->formType;
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
            else {
                logTrace(__METHOD__,$_GET);
                if (isset($_GET['sid'])){
                    $shop = $this->loadModel($_GET['sid'], 'Shop');
                    $this->setSessionShop($shop);
                    $form->shop_id = $shop->id;
                }
            }
        }
        return $form;
    }
            
    protected function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Shippings
        $sections->add(array('id'=>'shippings','name'=> Sii::t('sii','Shippings'),'heading'=>true,
                             'viewFile'=>'_shipping','viewData'=>array('dataProvider'=>$model->searchShippings())));
        return $sections->toArray();
    }      
        
}
