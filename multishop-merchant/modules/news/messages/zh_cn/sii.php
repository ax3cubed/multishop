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
    '"{object}" must be offline' => '"{object}"必须在线下状态',
    'Activate News' => '发布新闻',
    'Are you sure you want to activate this {object}?' => '您是否确定要上线{object}？',
    'Are you sure you want to deactivate this {object}?' => '您是否确定要下线{object}？',
    'Are you sure you want to delete headline' => '您是否确定要删除消息标题？',
    'Are you sure you want to delete this {object}?' => '您是否确定要删除{object}？',
    'create' => '创建',
    'Create' => '创建',
    'Create News' => '创建新闻',
    'Create Time' => '创建日期',
    'Deactivate News' => '取消发布新闻',
    'Delete News' => '删除新闻',
    'Save' => '储存',
    'Save News' => '储存新闻',
    'Select Shop' => '选择店铺',
    'Update' => '编辑',
    'Update News' => '编辑新闻',
    'Update Time' => '编辑日期',
    'View News' => '查阅新闻',
    'You can use Markdown language to format news.'=>'您可以使用Markdown格式语言来编辑新闻。',
    '{object} Activation' => '上线{object}',
    '{object} Deactivation' => '下线{object}',    
    '{shop}, last update at {datetime}'=>'{shop}，上一次编辑日期 {datetime}',
    //usability
    'Communicate and share your exciting news to customers.'=>'与客户沟通和分享您令人振奋的消息。',
    'Reach out to your customers for the good news you want to share.'=>'分享客户您的好消息。',
    'News with status {online} means everyone can read to your news.'=>'新闻状态显示{online}代表任何人可阅读。',
    'Edit news. News with status {online} means everyone can read to your news.'=>'编辑新闻。新闻状态显示{online}代表任何人可阅读。',
]);
