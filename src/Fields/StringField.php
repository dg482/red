<?php

namespace Dg482\Red\Fields;

use Dg482\Red\Values\StringValue;

/**
 * Class Text
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class StringField extends Field
{
    protected const FIELD_TYPE = 'string';

    /**
     * @param  string  $value
     * @return Field
     */
    public function setValue(string $value = ''): Field
    {
        $value = new StringValue(0, $value);
        $this->value = &$value;

        return $this;
    }

    /**
     * @return StringValue
     */
    public function getValue(): StringValue
    {
        return parent::getValue();
    }
}
