<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. or refer to LICENSE.md
 */
namespace tests\codeception\_support\models;

use yii\db\ActiveRecord;
/**
 * Description of Feature
 *
 * @author kwlok
 */
class Feature extends ActiveRecord 
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_feature';
    }
}
