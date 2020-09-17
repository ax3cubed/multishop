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
  'Account' => '账号',
  'Are you sure you want to delete this {object}?' => '您是否确定要删除此{object}？',    
  'Attribute' => '属性',
  'Attribute Options' => '属性选项',
  'Attribute Usage' => '属性使用',
  'Attributes' => '属性',
  'Attributes Management' => '属性管理',
  'Attribute|Attributes' => '属性',
  'Class' => '类',
  'Code' => '代码',
  'Code is already taken' => '代码已被使用',
  'Create' => '创建',
  'Create Attribute' => '创建属性',
  'Create Time' => '创建日期',
  'Delete Attribute' => '删除属性',
  'Fields with <span class="required">*</span> are required.' => '所有 <span class="required">*</span> 的栏项必须填写。',
  'ID' => '编号',
  'Key not found.' => '系统无法找到钥匙',
  'More information' => '查阅更多信息',
  'NA' => '不适用',
  'Name' => '名称',
  'Option Code' => '选项代码',
  'Option Code - Name' => '选项代码 - 名称',
  'Option Name' => '选项名称',
  'Options' => '选项',
  'Product Code' => '产品代码',
  'Product Name' => '产品名称',
  'Product Unit Price' => '产品单价',
  'Remove Option' => '删除选项',
  'Save' => '储存',
  'Save Attribute' => '储存属性',
  'Select' => '选择',
  'Select Class' => '选择类',
  'Select Input Type' => '选择输入类型',
  'Textfield' => '文本框',
  'Option cannot be deleted. This attribute has inventories.' => '此属性已有库存，属性选项无法删除。',
  'Type' => '类型',
  'Unauthorized Access' => '您无权限使用',
  'Update' => '更新',
  'Update Attribute' => '更新属性',
  'Update Time' => '更新日期',
  'View' => '查阅',    
  'View Attribute' => '查阅属性',    
]);
