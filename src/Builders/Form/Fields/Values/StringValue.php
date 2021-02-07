<?php

namespace Dg482\Red\Builders\Form\Fields\Values;

/**
 * Class StringValue
 * @package Dg482\Red\Values
 */
class StringValue extends FieldValue
{
    /**
     * StringValue constructor.
     * @param  int  $id
     * @param  string  $value
     */
    public function __construct(int $id = 0, string $value = '')
    {
        $this->setId($id);
        $this->setValue($value);
    }

    /**
     * @param  $value
     * @return StringValue
     */
    public function setValue($value): StringValue
    {
        $this->value = (string) $value;

        return $this;
    }
}
