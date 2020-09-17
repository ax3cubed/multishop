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
    'Are you sure you want to delete SKU' => '您是否确定要删除库存单位',
    'Are you sure you want to delete this {object}?' => '您是否确定要删除此{object}？',
    'Create' => '创建',
    'Create Inventory' => '创建库存',
    'Create {object}' => '创建{object}',
    'Decrease Stock' => '减少库存',
    'Delete {object}' => '删除{object}',
    'Enter "+" or "-" to indicate stock increment or decrement. E.g. +50, -50' => '您可输入 "+" 或 "-" 来表示库存增加或减少。例如 +50，-50。',
    'Increase Stock' => '增加库存',
    'Save' => '储存',
    'Save {object}' => '储存{object}',
    'Select Product' => '选择商品',
    'Select Shop' => '选择店铺',
    'Select {field}' => '选择{field}',
    'Update' => '更新',
    'Update {object}' => '更新{object}',
    'View' => '查阅',
    'View {object}' => '查阅{object}',    
    //usability
    'Inventory Starter Guide'=>'库存设置基本指南',
    'You need to have product first before you can set inventory.'=>'设置库存前您必须先添加产品。',
    'Know your inventories whether they are in high demand, run low or out of stock.'=>'了解你的库存是否有高需求，低位运行或脱销。',
    'Define SKU and inventory level by product code. SKU can be set according to combination of product attributes too.'=>'根据产品代码设置SKU及库存数量。您亦可根据产品属性组合设置SKU。',
    'All Inventories'=>'所有库存',
    'Low Quantity Inventories'=>'低位库存',
    'Out of Stock'=>'无库存',
    'all'=>'全部库存',
    'no-stock'=>'无库存',
    'low-stock'=>'低位库存',
]);
