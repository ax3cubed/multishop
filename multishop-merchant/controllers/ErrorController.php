<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("wcm.controllers.WcmLayoutTrait");
/**
 * Description of ErrorController
 *
 * @author kwlok
 */
class ErrorController extends SErrorController 
{
    use WcmLayoutTrait;
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        $this->loadWcmLayout($this);
        $this->htmlBodyCssClass .= ' error';
        $this->forceLogout = [403];
        
        if (!user()->isGuest){
            //when user has login but hit any error, use back authenticated layout
            $this->layout = Yii::app()->ctrlManager->authenticatedLayout;
            $this->headerView = Yii::app()->ctrlManager->authenticatedHeaderView;
            $this->footerView = Yii::app()->ctrlManager->authenticatedFooterView;
        }
    }     
}
