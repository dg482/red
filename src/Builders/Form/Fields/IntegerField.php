<?php

namespace Dg482\Red\Builders\Form\Fields;

use Dg482\Red\Builders\Form\Fields\Values\FieldValues;
use Dg482\Red\Builders\Form\Fields\Values\IntegerValue;

/**
 * Class IntegerField
 * @package Dg482\Red\Builders\Form\Fields
 */
class IntegerField extends Field
{
    /** @var bool */
    protected bool $unsigned = false;

    /** @var string */
    protected const FIELD_TYPE = 'integer';

    /**
     * IntegerField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        $intValue = new IntegerValue();
        $value = ($isMultiple) ? (new FieldValues())->push($intValue) : $intValue;
        $this->value = &$value;
    }
}
