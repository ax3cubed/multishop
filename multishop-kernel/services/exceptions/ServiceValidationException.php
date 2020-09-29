<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ServiceValidationException
 *
 * @author kwlok
 */
class ServiceValidationException extends ServiceException
{
    protected $name = 'Service Validation Error';
    protected $code = self::VALIDATION_ERROR;
}
