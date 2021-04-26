<?php

namespace Dg482\Red\Builders\Form\Fields;

/**
 * Class FloatField
 * @package Dg482\Red\Builders\Form\Fields
 */
class FloatField extends IntegerField
{
    /** @var string */
    protected const FIELD_TYPE = 'float';

    /** @var string */
    protected const FIELD_VALIDATE_TYPE = 'float';

    /**
     * IntegerField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }
}
