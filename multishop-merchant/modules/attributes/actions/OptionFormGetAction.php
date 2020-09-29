<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of OptionFormGetAction
 *
 * @author kwlok
 */
class OptionFormGetAction extends CAction 
{
    /**
     * Name of the session state variable to temporary store attribute objects. Defaults to 'undefined'
     * @var string
     */
    public $stateVariable = 'undefined';
    /**
     * Name of the option model, e.g. AttributeOption, ProductAttributeOption. Defaults to 'undefined'
     * @var string
     */
    public $optionModel   = 'undefined';
    
    public function init( ) 
    {
        parent::init();
    }
    /**
     * Get option form and add session
     * @param integer $type the type of attribute to get
     */
    public function run() 
    {
        if(isset($_GET['type'])) {//type for now is not in use, reserved for future use, e.g. support textfield etc
             $model = new $this->optionModel;
             $model->id = time();
             SActiveSession::add($this->stateVariable, $model);
             header('Content-type: application/json');
             echo CJSON::encode(array('status'=>'success',
                                      'form'=>Yii::app()->controller->renderPartial('_form_option',array('model'=>$model,'count'=>SActiveSession::count($this->stateVariable)),true),
                    ));
             Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }    
}