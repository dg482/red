<?php

namespace Dg482\Red\Builders\Form\Fields;

/**
 * Class PasswordField
 * @package Dg482\Red\Builders\Form\Fields
 */
class PasswordField extends StringField
{
    /** @var string */
    protected const FIELD_TYPE = 'password';

    /**
     * PasswordField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }
}
