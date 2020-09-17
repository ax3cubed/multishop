<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. or refer to LICENSE.md
 */
namespace tests\codeception\fixtures;

use yii\test\ActiveFixture;
/**
 * Description of OauthClientsFixture
 *
 * @author kwlok
 */
class OauthClientsFixture extends ActiveFixture
{
    public $modelClass = 'filsh\yii2\oauth2server\models\OauthClients';
}