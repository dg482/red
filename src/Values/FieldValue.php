<?php

namespace Dg482\Red\Values;

use Dg482\Red\FieldValueInterface;

/**
 * Class FieldValue
 * @package Dg482\Red\Values
 */
abstract class FieldValue implements FieldValueInterface
{
    /** @var int|array|string|null */
    protected int|array|string|null $value;

    /** @var int */
    protected int $id = 0;

    /**
     * @param  int|array|string|null  $value
     * @return FieldValueInterface
     */
    public function setValue(int|array|string|null $value): FieldValueInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string|int|array
     */
    public function getValue(): string|int|array
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
}
