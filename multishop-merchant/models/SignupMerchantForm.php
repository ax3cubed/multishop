<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.accounts.models.SignupForm');
Yii::import('common.modules.plans.models.Plan');
/**
 * Description of SignupMerchantForm
 *
 * @author kwlok
 */
class SignupMerchantForm extends SignupForm
{
    public $title;//this field is used to show form title and also different css display in different place
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            //specific merchant form rules; currently empty
        ]);
    }

    public function getTrialDuration()
    {
        return Plan::freeTrialInstance()->duration;
    }
}
