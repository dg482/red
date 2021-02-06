<?php

namespace Dg482\Red\Values;

use Dg482\Red\Interfaces\FieldValueInterface;

/**
 * Class FieldValue
 * @package Dg482\Red\Values
 */
abstract class FieldValue implements FieldValueInterface
{
    /** @var string */
    protected string $value = '';

    /** @var int */
    protected int $id = 0;

    /**
     * @param  $value
     * @return FieldValueInterface
     */
    public function setValue($value): FieldValueInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param  int  $id
     * @return FieldValueInterface
     */
    public function setId(int $id): FieldValueInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
