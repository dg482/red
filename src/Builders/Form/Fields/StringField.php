<?php

namespace Dg482\Red\Builders\Form\Fields;

use Dg482\Red\Builders\Form\Fields\Values\FieldValues;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;

/**
 * Class Text
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class StringField extends Field
{
    protected const FIELD_TYPE = 'string';

    /** @var StringValue|FieldValues */
    protected $value;

    /**
     * StringField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        $stringValue = new StringValue(0, '');
        $value = ($isMultiple) ? (new FieldValues())->push($stringValue) : $stringValue;
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
     * @return StringValue|FieldValues
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    public function getValue()
    {
        return parent::getValue();
    }
}