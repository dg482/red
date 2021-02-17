<?php

namespace Dg482\Red\Builders\Form\Fields\Values;

/**
 * Class IntegerValue
 * @package Dg482\Red\Builders\Form\Fields\Values
 */
class IntegerValue extends FieldValue
{
    /**
     * IntegerValue constructor.
     * @param  int  $id
     * @param  int  $value
     */
    public function __construct(int $id = 0, int $value = 0)
    {
        $this->setId($id);
        $this->setValue($value);
    }

    /**
     * @param  $value
     * @return IntegerValue
     */
    public function setValue($value): IntegerValue
    {
        $this->value = (int) $value;

        return $this;
    }
}
