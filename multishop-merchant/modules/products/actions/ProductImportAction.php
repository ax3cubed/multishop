<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.media.actions.AttachmentUploadAction");
Yii::import('common.modules.products.components.ProductImport');
/**
 * Description of ProductImportAction
 *
 * @author kwlok
 */
class ProductImportAction extends AttachmentUploadAction 
{
    public $deleteUploadTempFile = false;
    /**
     * Name of the model attribute used to store the Attachment specific info.
     * Refer to Model Attachment
     * @var string
     */
    public $filepathAttribute = 'filepath';
    /**
     * The main action that handles the file upload request.
     */
    public function run() 
    {
        if ($this->handleImporting()){
            if ($this->hasSessionFileProcessor){
                try {
                    $this->sessionFileProcessor->run();
                    $status = 'success';
                    $message = Sii::t('sii','Total <span style="color:red;">{count}</span> products are uploaded successfully.',array('{count}'=>$this->sessionFileProcessor->count));
                    //clear session processor when done
                    $this->clearSessionFileProcessor();
                    user()->setFlash(get_class(Product::model()),array(
                        'message'=>$message,
                        'type'=>$status,
                        'title'=>Sii::t('sii','Product Import Completed'),
                    ));
                    $redirect = url('products');
                    
                } catch (CException $ex) {
                    logError(__METHOD__.' '.$ex->getTraceAsString());
                    $status = 'failure';
                    $message = $this->getController()->getFlashAsString('error',$ex->getMessage(),Sii::t('sii','Product Import Errors'));
                }
            }
            else {
                $status = 'failure';
                $message = Sii::t('sii','Unauthorized Access');
            }
            
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'status'=>$status,
                'message'=>$message,
                'redirect'=>isset($redirect)?$redirect:null,
            ));
            Yii::app()->end();
        }
        else {
            //normal upload and delete handle
            parent::run();
        }
    }
    /**
     * Check if to handle importing
     * @return type
     */
    protected function handleImporting()
    {
        return isset($_GET['f']) && isset($_GET['process']) && $_GET['process']==1;
    }
    /**
     * @inheritdoc
     */
    protected function beforeReturnByModel($model) 
    {
        //need to use this scenario if not file name will be changed again before validation
        //@see SUploadForm::beforeValidate() 
        $model->setScenario($model->scenarioSkipSecureFileNames);
        $model->{$this->filepathAttribute} = $this->getPath().$model->{$this->fileNameAttribute};
        if (!$model->validate($this->filepathAttribute))
            return $model;//containing errors
        else {
            $this->setSessionFileProcessor($model->fileProcessor);
            return parent::beforeReturnByModel($model);
        }
    } 
    /**
     * Construct return attributes (including preview of products imported)
     * This is after a successful import file upload
     * @param type $model
     * @return type
     */
    protected function returnAttributes($model) 
    {
        return array(
            array(
                "name" => $model->{$this->displayNameAttribute},
                "type" => $model->{$this->mimeTypeAttribute},
                "size" => $model->{$this->sizeAttribute},
                "url" => $this->getFileUrl($model->{$this->fileNameAttribute}),
                "thumbnail_url" => $model->getThumbnailAssetUrl($this->getPublicPath(),$this->getController()->getAssetsURL('common.assets.images')),
                "delete_url" => $this->getController()->createUrl($this->getId(), array(
                    "_method" => "delete",
                    "file" => $model->{$this->fileNameAttribute},
                )),
                "delete_type" => "DELETE",
                "description" => $model->{$this->descriptionAttribute},
                //extra return attributes
                "import_preview" => $this->getPreview($model),
        ));
    }   
    
    protected function getPreview($model)
    {
        return $this->getController()->renderPartial('_import_preview',array(
                    'totalCount'=>$model->fileProcessor->count,
                    'dataProvider'=>$model->fileProcessor->previewDataProvider,
                    'columns'=>$this->getPreviewColumns(),
                    'filename'=>base64_encode($model->{$this->fileNameAttribute}),
                    'flashErrors'=>$this->getPreviewErrors($model->fileProcessor),
                ),true);
    }
    
    protected function getPreviewColumns()
    {
        $columns = [];
        foreach (ProductImportColumn::getAllColumns() as $column) {
            $columns[] = array(
                'name'=>$column,
                'htmlOptions'=>array('style'=>$column==ProductImportColumn::$productErrors?'':'text-align:center;','class'=>$column==ProductImportColumn::$productErrors?'error':''),
                'header'=> ProductImportColumn::getLabel($column),
                'type'=>'html',
            );
        }
        return $columns;
    }
    
    protected function getPreviewErrors($fileProcessor)
    {
        if ($fileProcessor->hasErrors){
            return $this->getController()->getFlashAsString(
                    'error',
                    Helper::htmlSmartKeyValues($fileProcessor->errors),
                    Sii::t('sii','Product Upload Errors'));
        }
        else {
            return '';
        }
    }    
    
    private $_sessionFileProcessor = '__importFileProcessor';
    protected function getHasSessionFileProcessor() 
    {
        return SActiveSession::get($this->_sessionFileProcessor)!=null;
    }
    
    protected function getSessionFileProcessor() 
    {
        return unserialize(SActiveSession::get($this->_sessionFileProcessor));
    }

    protected function setSessionFileProcessor($processor) 
    {
        SActiveSession::set($this->_sessionFileProcessor,serialize($processor));
    }

    protected function clearSessionFileProcessor() 
    {
        SActiveSession::set($this->_sessionFileProcessor,null);
    }
    
}
