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
    /** @var string */
    protected const FIELD_TYPE = 'string';

    /** @var string */
    protected const FIELD_VALIDATE_TYPE = 'string';

    /** @var StringValue|FieldValues */
    protected $value;

    /**
     * StringField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        $this->setMultiple($isMultiple);
        $stringValue = new StringValue(0, '');
        $value = ($this->isMultiple()) ? (new FieldValues())->push($stringValue) : $stringValue;
        $this->value = &$value;
    }

    /**
     * @param  string  $value
     * @return Field
     */
    public function setValue(string $value = ''): Field
    {
        if ($this->isMultiple()) {
            $this->value->push(new StringValue(0, $value));
        } else {
            $this->value->setValue($value);
        }

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
