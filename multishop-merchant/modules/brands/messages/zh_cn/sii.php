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
    'Are you sure you want to delete' => '您是否确定要删除',
    'Are you sure you want to delete this {object}?' => '您是否确定要删除此{object}？',    
    'Create' => '创建',
    'Create Brand' => '创建品牌',
    'Delete Brand' => '删除品牌',
    'Save' => '储存',
    'Save Brand' => '储存品牌',
    'Select Shop' => '选择店铺',
    'Update' => '更新',
    'Update Brand' => '更新品牌',
    'View' => '查阅',    
    'View Brand' => '查阅品牌',
    'This is the brand\'s SEO url. If you leave this field blank, it will be auto-generated based on brand name above.'=>'这是品牌搜索引擎优化网址。若您无填写, 系统将会根据以上的品牌名称自动生成优化网址。',
    //usability
    'Manage your product brands here.'=>'管理您所拥有的产品品牌。',
    'A good brand helps your products to sell better.'=>'好的品牌对产品的销售事半功倍。',
]);
