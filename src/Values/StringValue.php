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
     * @param  int|array|string|null  $value
     * @return FieldValueInterface
     */
    public function setValue(int|array|string|null $value): FieldValueInterface
    {
        $this->value = (string)$value;

        return $this;
    }
}
