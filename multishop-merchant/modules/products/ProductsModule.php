<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ProductsModule
 *
 * @author kwlok
 */
class ProductsModule extends SModule 
{
    /**
     * parentShopModelClass (model classname) means that this module needs to be attached to shop module 
     * as all products objects creation/update is assuming having shop_id in session 
     * 
     * parentShopModelClass (null or false) means that products module needs to define which shop products objects 
     * belongs to during creation/update 
     * 
     * @see SActiveSession::SHOP_ACTIVE
     * @property boolean whether parentShopModelClass is required.
     */
    public $parentShopModelClass = 'Shop';
    /**
     * parentProductModelClass (model classname) means that products module needs to be attached to product module 
     * as all products objects creation/update is assuming having product_id in session 
     * 
     * parentProductModelClass (null or false) means that products module needs to define which products objects 
     * belongs to during creation/update 
     * 
     * @see SActiveSession::PRODUCT_ACTIVE
     * @property boolean whether parentProductModelClass is required.
     */
    public $parentProductModelClass = 'Product';
    /**
     * Product Import upload action
     * @property string the action class used for attachment upload
     */
    public $attachmentUploadAction = 'common.modules.media.actions.AttachmentUploadAction';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'products',
                'pathAlias'=>'products.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'products.components.*',
            'products.models.*',
            'products.actions.*',
            $this->attachmentUploadAction,
            'common.services.ProductImportManager',
            'common.modules.products.models.*',
            'common.components.behaviors.*',
            'common.widgets.simagemanager.SImageManager',
            'common.widgets.simagemanager.models.SingleImageForm',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'shops'=>[
                    'common.modules.shops.models.*',
                ],
                'media'=>[
                    'common.modules.media.models.*',
                ],
                'attributes'=>[
                    'merchant.modules.attributes.models.*',
                ],
                'tasks'=>[
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.models.*',
                ],                                                         
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
                'itemcolumn'=>'common.widgets.SItemColumn',
            ],
            'images'=>[
                'loading'=>['common.assets.images'=>'loading.gif'],
                'datepicker'=> ['common.assets.images'=>'datepicker.gif'],                
            ],
        ]);  

        $this->defaultController = 'management';

        $this->registerScripts();
        $this->registerCommonFiles();
        $this->registerFormCssFile();
        $this->registerGridViewCssFile();          
    }
    /**
     * Module display name
     * @param $mode singular or plural, if the language supports, e.g. english
     * @return string the model display name
     */
    public function displayName($mode=Helper::SINGULAR)
    {
        return Sii::t('sii','Product|Products',[$mode]);
    }
    /**
    * @return ServiceManager
    */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=> [
                'class'=>'common.services.ProductManager',
                'model'=>['Category','Attribute','Product','ProductAttribute'],
                'htmlError'=>true,
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}