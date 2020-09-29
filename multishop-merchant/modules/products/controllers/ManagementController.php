<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.tasks.components.TransitionControllerActionTrait');
Yii::import('products.controllers.ProductBaseController');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends ProductBaseController 
{
    use TransitionControllerActionTrait;
    
    protected $ckeditorImageUploadAction = 'ckeditorimageupload';
    protected $importUploadAction = 'importupload';
    protected $stateVariable = SActiveSession::PRODUCT_SHIPPING;
    protected $formType = 'ProductForm';
    protected $formShippingType = 'ProductShippingForm';
    public    $shippingFormGetAction = 'shippingformget';

    public function init() 
    {
        parent::init();
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::PRODUCT_IMAGE; 
        $this->setSessionActionsExclude([
            $this->shippingFormGetAction,
            $this->ckeditorImageUploadAction,
            //when loading form it checks hasSEOConfigurator(), hence need to skip action exclude
            //@see Subscription::apiHasService why to use this name (as action id is set to this name)
            'ApiCheckAction',
        ]);
        //-----------------
        // ShopParentController Configuration
        //-----------------
        $this->showBreadcrumbsModule = true;
        $this->showBreadcrumbsController = false;
        $this->includePageFilter = true;
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Product';
        $this->route = 'products/management/index';
        $this->viewName = Sii::t('sii', 'Products Management');
        $this->sortAttribute = 'update_time';
        $this->searchMap = [
            'product' => 'name',
            'date' => 'create_time',
            'price' => 'unit_price',
            'shipping' => 'id',//attribute "id" is used as proxy to search into shipping names
            'status' => 'status',
        ];   
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'ProductFilterForm';
        $this->filterFormHomeUrl = url('products/management');
        //-----------------
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            'imageget',
            $this->ckeditorImageUploadAction,
            $this->shippingFormGetAction,
        ]);
        //-----------------//
    }
    /**
     * Declares class-based actions.
     */
    public function actions() 
    {
        $imageActions = $this->imageActions(SImageManager::MULTIPLE_IMAGES,[
            'formClass' => 'common.widgets.simagemanager.models.MultipleImagesForm',
            'uploadLimit'=>Config::getBusinessSetting('limit_product_image'),
        ]);
        
        return array_merge(parent::actions(),$imageActions,$this->transitionActions(), [
            'view' => [
                'class' => 'common.components.actions.LanguageReadAction',
                'model' => $this->modelType,
                'beforeRender' => 'setSessionProduct',
                'pageTitleAttribute' => 'name',
            ],
            'create' => [
                'class' => 'common.components.actions.LanguageCreateAction',
                'form' => $this->formType,
                'createModelMethod' => 'prepareForm',
                'setAttributesMethod' => 'setFormAttributes',
                'setModelAttributesMethod' => 'setModelAttributes',
                'service' => 'createProduct',
            ],
            'update' => [
                'class' => 'common.components.actions.LanguageUpdateAction',
                'form' => $this->formType,
                'loadModelMethod' => 'prepareForm',
                'setAttributesMethod' => 'setFormAttributes',
                'setModelAttributesMethod' => 'setModelAttributes',
                'service' => 'updateProduct',
            ],
            'delete' => [
                'class' => 'common.components.actions.LanguageDeleteAction',
                'model' => $this->modelType,
                'service' => 'deleteProduct',
                'beforeDelete' => 'beforeDeleteModel',
            ],
            $this->ckeditorImageUploadAction => [
                'class' => 'common.components.actions.CkeditorImageUploadAction',
            ],
            $this->shippingFormGetAction => [
                'class' => 'ShippingFormGetAction',
                'stateVariable' => $this->stateVariable,
                'formModel' => $this->formShippingType,
                'mandatoryGetParam'=>'ids',
                'formKeyStateVariable'=>'pid',
                'useLanguageForm'=>false,
            ],            
            $this->importUploadAction => [
                'class'=>'ProductImportAction',
                'formClass'=>'ProductImportForm',
                'stateVariable'=> SActiveSession::ATTACHMENT,
                'secureFileNames'=>true,
                'path'=>Yii::app()->getBasePath()."/www/uploads",
                'publicPath'=>'/uploads',
            ],            
        ]);
    }

    public function prepareForm($id = null) 
    {
        if (isset($id)) {//update action
            $form = new $this->formType($id);
            $form->loadLocaleAttributes();
            $form->loadSeoParamsAttributes();
            $form->shippings = $form->getChildForms();
            $form->categories = $form->getEncodedCategoryKeys();
            $this->setSessionProduct($form->modelInstance);
            //only load images when its GET request
            //for POST, should use session images instead
            if (!Yii::app()->request->isPostRequest)
                $form->modelInstance->loadSessionMedia();
            SActiveSession::load($this->stateVariable, $form, 'shippings');
        } 
        else {            
            $form = new $this->formType(Helper::NULL, 'create'); //there is a scenario "create" validation rule
            if ($this->hasParentShop())
                $form->shop_id = $this->getParentShop()->id;
            else {
                if (isset($_GET['sid'])) {
                    $shop = $this->loadModel($_GET['sid'], 'Shop');
                    $this->setSessionShop($shop);
                    $form->shop_id = $shop->id;
                }
            }
        }
        return $form;
    }

    public function setFormAttributes($form, $json = false) 
    {
        $form->assignLocaleAttributes($_POST[$this->formType], $json);
        if (!isset($_POST[$this->formType]['categories']))
            $form->categories = array();
        if (isset($_POST[$this->formShippingType])) {
            SActiveSession::clear($this->stateVariable); //we will use newly submitted $_POST[ProductForm][Shipping] as new base
            foreach ($_POST[$this->formShippingType] as $shippingForm) {
                //[1]reinstantiate tier form
                $_shipping = new $this->formShippingType($shippingForm['product_id']);
                $_shipping->assignLocaleAttributes($shippingForm, true); //serialize multi-lang attribute values
                if ($_shipping->product_id == null)
                    $_shipping->product_id = 0; //temp assigned id for validation use only
                    
                //[2]transfer back errors if any from previous activity
                foreach ($form->shippings as $__s)
                    $_shipping->addErrors($__s->getErrors());
                //[3]setup new session tier based on newly submitted $_POST[$this->formShippingType]
                logTrace(__METHOD__ . ' $_shipping->getAttributes()', $_shipping->getAttributes());
                SActiveSession::add($this->stateVariable, $_shipping);
            }//end for loop
            $form->shippings = SActiveSession::load($this->stateVariable);
        }
        return $form;
    }

    public function setModelAttributes($form) 
    {
        //[1]copy form attributes to model attributes
        $form->modelInstance->attributes = $form->getModelAttributes();
        //[2]copy form tiers attributes to model options attributes
        $form->modelInstance->shippings = $form->getChildModels();
        $form->modelInstance->categories = $form->getCategoryModels();
        //[3]Populate seo param fields
        $form->modelInstance->meta_tags = $form->seoParamsString;
        return $form;
    }

    public function beforeDeleteModel($model) 
    {
        SActiveSession::set(SActiveSession::PRODUCT_ACTIVE, null);
    }

    protected function getSectionsData($model, $form = false) 
    {
        $sections = new CList();
        if (!$form) {//View
            //section 1: Attributes
            $sections->add(['id' => 'attribute',
                'name' => Sii::t('sii', 'Attributes'),
                'heading' => true, 'top' => true,
                'viewFile' => '_attr', 'viewData' => ['dataProvider' => $model->searchAttributes()]]);
            //section 2: Shippings
            $sections->add(['id' => 'shipping',
                'name' => Sii::t('sii', 'Shippings'),
                'heading' => true,
                'viewFile' => '_shipping', 'viewData' => ['dataProvider' => $model->searchShippings()]]);
            //section 3: Inventories
            $sections->add(['id' => 'inventory',
                'name' => Sii::t('sii', 'Inventories'),
                'heading' => true,
                'viewFile' => '_inventory', 'viewData' => ['dataProvider' => $model->searchInventories()]]);
            //section 4: Specification
            $sections->add(['id' => 'spec',
                'name' => $model->getAttributeLabel('spec'),
                'heading' => true,
                'html' => $model->languageForm->renderForm($this, Helper::READONLY, ['spec'], true)]);
            //section 5: SEO
            $sections->add(['id' => 'seo',
                'name' => $model->getAttributeLabel('seo'),
                'heading' => true,
                'viewFile' => '_seo', 'viewData' => ['model' => $model]]);
        } else {//Update or Create
            //section 1: Brand
            $sections->add(['id' => 'brand',
                'name' => $model->getAttributeLabel('brand_id'),
                'heading' => true, 'top' => true,
                'viewFile' => '_form_brand', 'viewData' => ['model' => $model]]);
            //section 2: Category
            $sections->add(['id' => 'category',
                'name' => ProductCategory::model()->displayName(Helper::PLURAL),
                'heading' => true,
                'viewFile' => '_form_category', 'viewData' => ['model' => $model]]);
            //section 3: Shippings
            $sections->add(['id' => 'shipping',
                'name' => Sii::t('sii', 'Shippings'),
                'heading' => true,
                'viewFile' => '_form_shippings', 'viewData' => ['model' => $model]]);
            //section 4: Specification
            $sections->add(['id' => 'spec',
                'name' => $model->getAttributeLabel('spec'),
                'heading' => true,
                'viewFile' => '_form_spec', 'viewData' => ['model' => $model]]);
            //section 5: SEO
            if ($model->hasSEOConfigurator()){
                $sections->add(['id' => 'seo',
                    'name' => $model->getAttributeLabel('seo'),
                    'heading' => true,
                    'viewFile' => '_form_seo', 'viewData' => ['model' => $model]]);
            }
        }
        return $sections->toArray();
    }

    protected function getProductShippingList($product, $locale = null) 
    {
        $list = new CList();
        foreach ($product->searchShippings()->data as $productShipping) {
            $list->add(
                    Helper::htmlColorText($productShipping->shipping->getStatusText()) . ' ' .
                    CHtml::link(CHtml::encode($productShipping->shipping->displayLanguageValue('name', $locale)), $productShipping->shipping->viewUrl)
            );
        }
        return $list->count() == 0 ? Sii::t('sii', 'Not Associated') : Helper::htmlList($list, array('style' => 'margin:0;padding:5px;'));
    }

    protected function getProductCampaignList($product, $locale = null) 
    {
        $list = new CList();
        foreach (CampaignBga::model()->findAllByAttributes(array('buy_x' => $product->id)) as $model) {
            $list->add(
                    Helper::htmlColorText($model->getStatusText()) . ' ' .
                    CHtml::link(Helper::htmlColorTag($model->getType(), $model->getTypeColor()) . ' ' .
                            ($model->hasExpired() ? $model->getExpiredTag() : '') . ' ' .
                            CHtml::encode($model->displayLanguageValue('name', $locale)), $model->viewUrl)
            );
        }
        return $list->count() == 0 ? Sii::t('sii', 'Not Associated') : Helper::htmlList($list, array('style' => 'margin:0;padding:5px;'));
    }

    protected function getShippingRateData($shipping) 
    {
        if ($shipping->type == Shipping::TYPE_TIERS)
            return Helper::htmlList($shipping->getShippingRateText());
        else
            return $shipping->getShippingRateText();
    }
    /**
     * View Import Products page
     */
    public function actionImport() 
    {
        if (isset($_GET['view'])){
            $model = $this->loadModel($_GET['view'], 'ProductImport');
            if ($this->module->serviceManager->checkObjectAccess(user()->getId(),$model)){
                $this->pageTitle = Sii::t('sii','Prouct Import');
                $this->render('import_view',array('model'=>$model));
            }
            else
                throwError403(Sii::t('sii','Unauthorized Access'));
        }
        elseif (isset($_GET['history'])){
            $import = new ProductImport();
            $import->shop_id = $this->getParentShop()->id;
            $this->pageTitle = Sii::t('sii','Import History');
            $this->render('import_history',array('dataProvider'=>$import->search()));
        }
        else 
            $this->render('import');
    }
    
    protected function getImportUploadForm() 
    {
        $importForm = new ProductImportForm();
        $importForm->uploadRoute = url($this->module->id .'/'.$this->id .'/'.$this->importUploadAction);
        $importForm->obj_id =  $this->hasParentShop()?$this->getParentShop()->id:null;
        $importForm->obj_type = $this->hasParentShop()?$this->getParentShop()->tableName():null;
        return $this->widget($importForm->uploadWidget, array(
            'url' => $importForm->uploadRoute,
            'model' => $importForm,
            'attribute'=>$importForm->fileAttribute,
            'multiple' => false,
            'autoUpload'=>true,
            'uploadView'=>$importForm->uploadView,
            'downloadView'=>$importForm->downloadView,
            'formView'=>$importForm->formView,
            'options'=>array('previewMaxWidth'=>30,'previewMaxHeight'=>30),
            'htmlOptions'=>array(
                'class'=>$importForm->formClass,/*key identifier used at tasks.js*/
                'placeholder'=>'',
            ),
        ));
    }       
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return CDbCriteria
     */
    public function getSearchCriteria($model)
    {
        $criteria = new CDbCriteria;
        $criteria = QueryHelper::parseLocaleNameSearch($criteria, 'name', $model->name);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        $criteria->compare('unit_price',$model->unit_price,true);
        $criteria->compare('status', QueryHelper::parseStatusSearch($model->status,ProductFilterForm::STATUS_FLAG));
        if (!empty($model->id))//attribute "id" is used as proxy to search into shipping names
            $criteria->addCondition($model->constructShippingInCondition($model->id,$this->getParentShop()->id));
        
        return $criteria;
    }    
    /**
     * OVERRIDE METHOD
     * @see ShopParentController::getWizards
     * @return type
     */
    public function getWizards($flash)
    {
        $productWizard =  $this->hasParentProduct() ? $this->loadWizards($flash,user(),$this->getParentProduct(),'ProductWizardStarterProfile') :  null;
        //merging shop wizards into product wizards
        return $this->loadWizards($productWizard,user(),$this->hasParentShop()?$this->getParentShop():null,'ShopWizardStarterProfile');
    }
    
}
