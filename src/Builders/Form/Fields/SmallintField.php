<?php

namespace Dg482\Red\Builders\Form\Fields;

/**
 * Class SmallintField
 * @package Dg482\Red\Builders\Form\Fields
 */
class SmallintField extends IntegerField
{
    /** @var string */
    protected const FIELD_TYPE = 'smallint';

    /**
     * IntegerField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }
}
