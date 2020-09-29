<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of PaymentBaseGateway
 *
 * @author kwlok
 */
abstract class PaymentBaseGateway extends CApplicationComponent 
{
    const PAID   = 'P';
    const UNPAID = 'U';    
    
    abstract public function process($payment);
}
