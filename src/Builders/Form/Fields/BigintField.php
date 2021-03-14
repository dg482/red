<?php

namespace Dg482\Red\Builders\Form\Fields;

/**
 * Class BigintField
 * @package Dg482\Red\Builders\Form\Fields
 */
class BigintField extends IntField
{
    /** @var string */
    protected const FIELD_TYPE = 'bigint';

    /**
     * BigintField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }
}
