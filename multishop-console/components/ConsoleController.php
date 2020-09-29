<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * A speical controller designed to work with CConsoleApplication
 *
 * @see NotificationManager::controller()
 * @author kwlok
 */
class ConsoleController extends CController 
{
    /**
     * Render view 
     * For CConsoleApplication, a separate controller have to be created, and use renderInternal; renderPartial wont' work with CConsoleApplication
     * 
     * @param type $view
     * @param type $params
     * @return type
     */
    public function renderPartial($view,$params=array(),$return=false, $processOutput = false)
    {
        $viewFile = Yii::getPathOfAlias($view).'.php';
        if ($return)
            return $this->renderInternal($viewFile,$params,true);   
        else
            $this->renderInternal($viewFile,$params);   
    }
}
