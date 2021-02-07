<?php

namespace Dg482\Red\Builders\Form;

/**
 * Trait AttributeTrait
 * @package Dg482\Red\Fields
 */
trait AttributeTrait
{

    /** @var array $attributes */
    protected array $attributes = [];

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return $this->attributes;
    }
}