<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of OptionFormDelAction
 *
 * @author kwlok
 */
class OptionFormDelAction extends CAction 
{
    /**
     * Name of the session state variable to temporary store attribut objects. Defaults to 'undefined'
     * @var string
     */
    public $stateVariable = 'undefined';
    
    public function init( ) 
    {
        parent::init();
    }
    /**
     * Delete option form and clear session
     * @param integer $key the ID of session object to delete
     */
    public function run() 
    {
        if(isset($_GET['key'])) {
            header('Content-type: application/json');
            $key = $_GET['key'];
            switch ($key) {
                 case 'all':
                    SActiveSession::clear($this->stateVariable);
                    echo CJSON::encode(array('status'=>'success'));
                    break;
                 default:
                     
                    if (SActiveSession::remove($this->stateVariable,$key))
                        echo CJSON::encode(array('status'=>'success','key'=>$key,'count'=>SActiveSession::count($this->stateVariable)));
                    else
                        echo CJSON::encode(array('status'=>'failure','key'=>$key,'message'=>Sii::t('sii','Key not found.')));
                    break;
            }
            Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access'));
    }  
}