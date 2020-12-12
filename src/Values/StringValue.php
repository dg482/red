<?php

namespace Dg482\Red\Values;

use Dg482\Red\FieldValueInterface;

/**
 * Class StringValue
 * @package Dg482\Red\Values
 */
class StringValue extends FieldValue
{
    /**
     * @param  $value
     * @return FieldValueInterface
     */
    public function setValue($value): FieldValueInterface
    {
        $this->value = (string)$value;

        return $this;
    }
}
