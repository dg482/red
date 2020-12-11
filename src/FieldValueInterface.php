<?php

namespace Dg482\Red;

/**
 * Interface FieldValue
 * @package Dg482\Red
 */
interface FieldValueInterface
{
    /**
     * @param  mixed|string|int|array|null $value
     * @return FieldValueInterface
     */
    public function setValue(string|int|array|null $value): FieldValueInterface;

    /**
     * @return string|int|array
     */
    public function getValue(): string|int|array;

    /**
     * @param  int  $id
     * @return FieldValueInterface
     */
    public function setId(int $id): FieldValueInterface;

    /**
     * @return int
     */
    public function getId():int;
}
