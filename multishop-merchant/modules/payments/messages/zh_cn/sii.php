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
/**
 * [4] It merges further with message translation from payment plugins messages sources
 */
$pluginsSource = [];//empty
foreach (Yii::app()->getModule('payments')->plugins as $plugin) {
    $pluginsSource = array_merge($pluginsSource,require(Yii::app()->getModule('payments')->pluginPath.'/'.$plugin['name'].'/messages/zh_cn/sii.php'));
}

return array_merge($appMessages,$moduleMessages,$pluginsSource,[
    //put local translation here e.g. 'translate'=>'翻译',
    '"{name}" is activated successfully.' => '上线"{name}"成功。',
    '"{name}" is deactivated successfully.' => '下线"{name}"成功。',
    '"{object}" must be offline' => '"{object}"必须在线下状态',
    'Activate Payment Method' => '上线付款方式',
    'API Mode'=>'API环境',
    'Are you sure you want to activate this {object}?' => '您是否确定要上线{object}？',
    'Are you sure you want to deactivate this {object}?' => '您是否确定要下线{object}？',
    'Are you sure you want to delete' => '您是否确定要删除',
    'Are you sure you want to delete this {object}?' => '您是否确定要删除{object}？',
    'Below is the template message will be displayed on the order confirmation page after customer has successfuly placed their order.'=>'当客户成功完成下单时，以下的模板文本将会被显示。',
    'Create' => '创建',
    'Create Payment Method' => '创建付款方式',
    'Deactivate Payment Method' => '下线付款方式',
    'Delete Payment Method' => '删除付款方式',
    'Shop is currently online and requires at least one online payment method.' => '店铺目前在线上必须拥有至少一个在线上的付款方式',
    '{object} Activation' => '上线{object}',
    '{object} Deactivation' => '下线{object}',    
    //usability
    'Setup payment methods that you want to accept to pay for your orders. You can set multiple online and offline payment methods.'=>'设置店铺所接受的订单付款方式。您可设置多个线上和线下的付款方式。',
    'You have many payment methods, either online or offline, to choose from to pay for your order.'=>'您有多种线上或线下付款方式供选择来支付订单。',
    'Payment method with status {online} means everyone can start use it to pay your order.'=>'付款方式状态显示{online}代表每个人可以开始使用来支付订单。',
    'Edit payment method setup. Payment method with status {online} means everyone can start use it to pay your order.'=>'编辑付款方式设置。付款方式状态显示{online}代表每个人可以开始使用来支付订单。',
]);
