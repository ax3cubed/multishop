<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ReceiptController
 *
 * @author kwlok
 */
class ReceiptController extends BillingBaseController  
{
    public function init()
    {
        parent::init();
        $this->pageTitle = Sii::t('sii','Receipt');
        $this->modelType = 'Receipt';
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            'view'=>array(
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'receipt_no',
                'finderMethod'=>'receiptNo',
            ),       
            'download'=>array(
                'class'=>'common.components.actions.DownloadAction',
                'model'=>'Receipt',
            ),              
        ));
    }      
    /**
     * Oput receipt items in html form
     * @param type $model
     * @return type
     */
    public function htmlItems($model)
    {
        $html = '';
        $items = [];
        foreach ($model->itemsData as $item) {

            foreach ($item as $key => $field) {
                if ($key=='amount'){
                    $label = Sii::t('sii',$model->getAttributeLabel('amount'));
                    $value = $model->formatCurrency($item['amount'],$item['currency']);
                }
                elseif (array_key_exists($key, $model->attributeLabels())){
                    $label = Sii::t('sii',$model->getAttributeLabel($key));
                    $value = $field;
                }
                else {
                    $label = $key;
                    $value = $field;
                }
                $items[] = ['label'=>$label,'value'=>$value,'type'=>'raw'];
            }
            
            $html .= $this->widget('common.widgets.SDetailView', array(
                'data'=>$model,
                'columns'=>[$items],
            ),true);
            $items = [];
        }
        return $html;
    }
    
}
