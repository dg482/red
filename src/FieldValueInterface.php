<?php

namespace Dg482\Red;

/**
 * Interface FieldValue
 * @package Dg482\Red
 */
interface FieldValueInterface
{
    /**
     * @param  $value
     * @return FieldValueInterface
     */
    public function setValue($value): FieldValueInterface;

    /**
     * @return string
     */
    public function getValue(): string;

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
