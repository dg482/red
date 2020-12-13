<?php

namespace Dg482\Red\Exceptions;

use Exception;

/**
 * Class BadVariantKey
 * @package Dg482\Red\Exceptions
 */
class BadVariantKeyException extends Exception
{
    protected $message = 'Empty "id" or "value" key the variant array.';
}
