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
    'Are you sure you want to delete this {object}?' => '您是否确定要删除此{object}？',
    'Create' => '创建',
    'Create Tax'=> '创建税费',
    'Delete Tax' => '删除税费',
    'For example, input 0.07 to indicate 7%'=>'例如：输入 0.07 表示 7 个百分比',
    'Save' => '储存',
    'Update Tax' => '更新税费', 
    'Add and manage taxes you want to charge on top of total order price e.g. VAT, GST.'=>'添加与管理您要对订单总额所增收的税费，例如增值税，消费税。',
    'Apply tax charge to your order.'=>'设置增收订单税费。',
    'Tax with status {online} means your order will start charge customer to pay tax over total order price.'=>'税费状态显示{online}代表您开始向客户增收订单总额税费。',
    'Edit tax setup. Tax with status {online} means your order will start charge customer to pay tax over total order price.'=>'编辑税务设置。税费状态显示{online}代表您开始向客户增收订单总额税费。',
]);
