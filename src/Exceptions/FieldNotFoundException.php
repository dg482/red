<?php

namespace Dg482\Red\Exceptions;

use Exception;

/**
 * Class FieldNotFoundException
 * @package Dg482\Red\Exceptions
 */
class FieldNotFoundException extends Exception
{
    public $message = 'Field not found';
}
