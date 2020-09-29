<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace app\modules\v1\actions;

/**
 * Description of ModelCreateAction
 *
 * @author kwlok
 */
class ModelCreateAction extends ModelServiceAction
{
    /**
     * @return ApiModel
     */
    public function run()
    {
        $model = $this->controller->getCreateApiModel();
        return $this->invokeService($model);
    }     
}
