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
    'Activate {object}' => '上线{object}',
    'Add Tier' => '添加分层规则',
    'Are you sure you want to activate this {object}?' => '您是否确定要上线{object}？',
    'Are you sure you want to deactivate this {object}?' => '您是否确定要下线{object}？',
    'Are you sure you want to delete' => '您是否确定要删除',
    'Are you sure you want to delete this {object}?' => '您是否确定要删除此{object}？',
    'Associated Products' => '使用此货运的产品',
    'Create' => '创建',
    'Create Shipping' => '创建货运',
    'Create Zone' => '创建区域',
    'Delete Shipping' => '删除货运',
    'Delete Zone' => '删除区域',
    'Please add tier rule' => '请添加分层规则',
    'Please select tiered fee base' => '请选择分层运费计算方式',
    'Remove Option' => '删除规则',
    'Save' => '储存',
    'Save Shipping' => '储存货运',
    'Save Zone' => '储存区域',
    'Select Base' => '选择计算方式',
    'Select Country' => '选择国家',
    'Select Method' => '选择货运方式',
    'Select Shop' => '选择店铺',
    'Select Type' => '选择类型',
    'Select Zone' => '选择区域',
    'Update' => '更新',
    'Update Shipping' => '更新货运',
    'Update Zone' => '更新区域',
    'View' => '查阅',
    'View Shipping' => '查阅货运',
    'View Zone' => '查阅区域',
    '{object} Activation' => '上线{object}',
    '{object} Deactivation' => '下线{object}',   
    //usability
    'Define how you ship your products and the shipping rates required. Add as many shipping methods as you want.'=>'设置您如何运送产品及所需的运费。您可添加无限个货运方式。',
    'Specify the zone areas that you want to ship your products.'=>'指定您所要的产品运送区域范围。',
    'Shipping Starter Guide'=>'货运方式基本指南',
    'You need to first setup the zone areas that you want to ship your products.'=>'您必须先设置您的产品所有运送到的区域。',
    'Shipping methods can be by delivery or self-pickup, and with different options of shipping fee setup such as flat fee or tiered based.'=>'您可选择货运到家或客户亲自领取的货运方式，并设置不同的运费机制例如定价或分层运费等。',
    'Shipping with status {online} means everyone can view and choose as a product shipping option.'=>'货运方式状态显示{online}代表每个人可以查看并选择为产品货运选项。',
    'Edit your shipping setup. Shipping with status {online} means everyone can view and choose as a product shipping option.'=>'编辑货运方式设置。货运方式状态显示{online}代表每个人可以查看并选择为产品货运选项。',
    'Specify the zone areas that you want to ship your products. Shipping zone is defined by country.'=>'指定您所要的产品货运区域范围。区域是以国家来定义。',
    'Shipping zone is defined by country.'=>'货运区域是根据国家来定义。',
]);
