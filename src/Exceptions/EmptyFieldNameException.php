<?php

namespace Dg482\Red\Exceptions;

use Exception;

/**
 * Class EmptyFieldNameException
 * @package Dg482\Red\Exceptions
 */
class EmptyFieldNameException extends Exception
{
    public $message = 'Empty Form Field name!';
}