<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.schildform.actions.ChildFormGetAction');
/**
 * Description of TierFormGetAction
 *
 * @author kwlok
 */
class TierFormGetAction extends ChildFormGetAction  
{
    /**
     * Get subcategory form and add session
     * @param integer $type the type of attribute to get
     */
    public function run() 
    {
        if (isset($this->mandatoryGetParam)) {
            if (!isset($_GET[$this->mandatoryGetParam]) && !isset($_GET[$this->formKeyStateVariable])) 
                throwError403(Sii::t('sii','Unauthorized Access'));        
        }
        
        $form = new $this->formModel($_GET[$this->formKeyStateVariable]);
        if ($this->useLanguageForm && !$form instanceof LanguageForm)
            throw new CException(Sii::t('sii','Invalid form.'));
        $form->id = $this->getTempId();
        $form->base = $_GET[$this->mandatoryGetParam];
        SActiveSession::add($this->stateVariable, $form);             
        header('Content-type: application/json');
        echo CJSON::encode(array(
            'status'=>'success',
            'form'=>Yii::app()->controller->renderPartial($this->formView,array('form'=>$form),true),
        ));
        Yii::app()->end();  
    }    
}
