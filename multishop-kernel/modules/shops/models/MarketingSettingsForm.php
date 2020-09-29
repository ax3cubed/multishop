<?php
/**
 * This file is part of Multishop.org (https://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.models.BaseShopSettingsForm');
Yii::import('common.modules.shops.models.settings.MarketingSettingsTrait');
/**
 * Description of MarketingSettingsForm
 *
 * @author kwlok
 */
class MarketingSettingsForm extends BaseShopSettingsForm 
{
    use MarketingSettingsTrait;    
}