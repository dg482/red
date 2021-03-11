<?php

namespace Dg482\Red\Builders\Form\Fields;

/**
 * Class Hidden
 * @package Dg482\Red\Builders\Form\Fields
 */
class HiddenField extends StringField
{
    /** @var string */
    protected const FIELD_TYPE = 'hidden';

    /**
     * IntegerField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }
}
