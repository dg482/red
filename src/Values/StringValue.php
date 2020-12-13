<?php

namespace Dg482\Red\Values;

use Dg482\Red\FieldValueInterface;

/**
 * Class StringValue
 * @package Dg482\Red\Values
 */
class StringValue extends FieldValue
{
    public function __construct(int $id = 0, string $value = '')
    {
        $this->setId($id);
        $this->setValue($value);
    }

    /**
     * @param  $value
     * @return FieldValueInterface
     */
    public function setValue($value): FieldValueInterface
    {
        $this->value = (string) $value;

        return $this;
    }
}
