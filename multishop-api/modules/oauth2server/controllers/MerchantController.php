<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace app\modules\oauth2server\controllers;

/**
 * Description of MerchantController
 *
 * @author kwlok
 */
class MerchantController extends LoginController 
{
    protected $identityClass = '\IdentityMerchant';
}
