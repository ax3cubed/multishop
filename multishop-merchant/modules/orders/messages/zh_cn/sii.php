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
    //PO messages
    'This lists all your purchase orders. A purchase order can contain multiple shipping orders.'=>'这里列出所有的采购订单。同一个订单可拥有多个运单。',
    'Paid orders are orders that customers have made payment but pending your verification to confirm the order.'=>'已付款运单是客户已经支付但您尚未验证确认的运单。',
    'Paid purchase orders are orders that customers have made payment but pending your verification to confirm the order.'=>'已付款订单是客户已经支付但您尚未验证确认的订单。',
    'Unpaid orders are orders that customers have chosen offline payment method for and yet to make payment.'=>'未付款订单代表客户选择使用线下付款方式购买且尚未付款。',
    'Rejected orders are orders that had failed payment verification.'=>'已拒绝运单是指未能通过支付验证的运单。',
    'These are orders with payment made or verified, but pending fulfillment.'=>'此类运单为已付款或已验证，但仍未履行的运单。',
    'These are orders that have been fully fulfilled and all their purchased items are shipped.'=>'已履行订单代表所有购买商品都已全部发货。',
    'These are orders that have been partially fulfilled and there are pending purchased items to be shipped or in other status.'=>'部分履行订单代表非全部购买商品都已发货, 尚有商品待发货或处其他状态。',
    'Cancelled orders are orders that customers had changed their mind for the purchase for any reasons.'=>'已取消订单代表由于某种原因客户决定不下单并取消购买。',
    'Refunded orders are orders that you have refunded customers when you cancelled orders with payment made.'=>'此类运单为那些已取消但已支付且您已向客户退款的运单。',
    //SO messages
    'All shipping orders are processed on per shipping basis. If customer purchases multiple products by choosing different shippings in one purchase order, a purchase order can contain multiple shipping orders. '=>'所有运单是按个别的货运方式来处理。若客户在同一个采购订单里购买不同的产品并选择不同的货运，同一个采购订单可拥有多个运单。',
    'Fulfilled orders are orders that you had shipped the purchased products to customers.'=>'已履行运单代表您已根据运单发货给客户。',
    'Cancelled orders are orders that you do not want to process for any reasons.'=>'已取消运单代表您根据某种理由或命令不想处理的单子。',
 ]);
