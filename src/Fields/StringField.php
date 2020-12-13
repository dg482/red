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

    /** @var StringValue */
    protected $value;

    public function __construct()
    {
        $value = new StringValue(0, '');
        $this->value = &$value;
    }

    /**
     * @param  string  $value
     * @return Field
     */
    public function setValue(string $value = ''): Field
    {
        $this->value->setValue($value);

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
