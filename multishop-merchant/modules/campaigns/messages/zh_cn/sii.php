<?php
/**
 * Module Message translations (this file must be saved in UTF-8 encoding).
 * It merges messages from below sources in sequence:
 * [1] application level messages 
 * [2] common module level messages (inclusive of kernel common messages)
 * [3] local module level messages
 */
$appMessages = require(Yii::app()->basePath.DIRECTORY_SEPARATOR.'messages/zh_cn/sii.php');//already inclusive kernel messages
$moduleName = basename(dirname(__DIR__, 2));//move two levels up and grep module name
$moduleMessages = Sii::findMessages(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.KERNEL_NAME,$moduleName,'zh_cn');
return array_merge($appMessages,$moduleMessages,[
    //put local translation here e.g. 'translate'=>'翻译',
    '"{name}" is activated successfully.' => '上线"{name}"成功。',
    '"{name}" is deactivated successfully.' => '下线"{name}"成功。',    
    '- Blank Product -' => '－无产品－',
    'Activate {object}' => '上线{object}',
    'all' => '全部',
    'All' => '全部',
    'Are you sure you want to activate this {object}?' => '您是否确定要上线{object}？',
    'Are you sure you want to deactivate this {object}?' => '您是否确定要下线{object}？',
    'Are you sure you want to delete this {object}?' => '您是否确定要删除此{object}？',
    //usability
    'You have many ways to drive sales through campaigns such as store-wide discount, promotional code, or Buy X Get Y etc.'=>'您有多种营销方法来拉动销售，例如全店折扣，优惠码，或买X送Y等。',
    'Run a store-wide campaign to boost sales.'=>'来一个全店折扣大优惠刺激销售。',
    'Store-wide discounts by minimum purchased amount or purchased quantity.'=>'以最低购买额或最低购买量作全店折扣大优惠。',
    'Reward your customers promocodes for better price.'=>'奖赏您的尊贵客户优惠码以获取更多折扣。',
    'Customer can enter code to enjoy additional discount after order price.'=>'客户输入优惠码可享受订单价格后的额外折扣。',
    'Attract more customers by offering various kind of product level promotions through configurable and powerful BGA campaigns.'=>'通过配置和强大的BGA产品促销，提供多变化的促销活动来吸引更多的客户。',
    'BGA stands for "Buy", "Get", "At": Buy X Product Get Y Product At special offer.'=>'BGA的全称是“买”，“送”，“得”：购买X产品送Y产品於特别优惠。',
    'Campagin with status {online} means it is live and every one can enjoy the offer.'=>'促销状态若显示{online}代表它正式上线任何人可享受优惠。',
    'Edit campaign setup. Campagin with status {online} means it is live and every one can enjoy the offer.'=>'编辑营销设置。促销状态若显示{online}代表它正式上线任何人可享受优惠。',
    'Campaign Name'=>'营销活动名称',
    'Campaign or Promotional Code'=>'营销活动或优惠码',
    'Sale Campaign Name'=>'Sale促销活动名称',
    'BGA Campaign Name'=>'BGA产品促销活动名称',
    'Promocode Campaign Name'=>'优惠码促销活动名称',
    'Enter any campaign name'=>'输入任何营销活动名称',
    'Enter any campaign name or promocode'=>'输入任何营销活动名称或优惠码',
    'Enter any sale campaign name'=>'输入任何Sale促销活动名称',
    'Enter any promocode'=>'输入任何优惠码',
    'Offer Type'=>'优惠类型',
    'Select Offer Type'=>'选择优惠类型',
    'Invalid offer type.'=>'错误优惠类型',
    'Promotional Code'=>'优惠码',
]);
