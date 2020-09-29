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
    //merchant items
    'This lists all the items that you have sold.'=>'这里列出所有您已售出的商品。',
    'Unpaid items are items that customer have not made payment.'=>'未付款商品正待客户支付。',
    'Paid items are items that your pending payment verification before shipping.'=>'已付款商品需等您确认收到付款才能发货。',
    'These are confirmed purchased items that should be get started to ship them.'=>'已购买商品代表您已确认并开始发货。',
    'These are items that already shipped.'=>'已发货商品代表您已完成发货。',
    'These are items that rejected due to unsuccessful payment verification.'=>'已拒绝商品代表您无法核对付款。',
    'These are items that either cancelled by customer or yourselves. You can check the reason of cancellation.'=>'这类商品代表店家或客户取消订单。您可查看取消的原因。',
    'Returned items are items that you had accepted customers item return requests. Once you accept the return request, refund process will kick in.'=>'已退货商品代表您已同意客户的商品退还要求。当客户将商品退还时，您将会对退还商品退款。',
    'Received items are items that already acknowledged by customer.'=>'已签收商品代表客户已确认收取。',
    'Refunded items are items that had been cancelled, returned or refunded.'=>'已退款商品代表您已将订单取消并已退款。',
    'Pending items are items that currently being processed.'=>'处理中商品表示您正在进行处理。',
]);
