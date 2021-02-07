<?php

namespace Dg482\Red\Builders\Form\Fields\Values;

/**
 * Class FieldValues
 * @package Dg482\Red\Values
 */
class FieldValues
{
    protected array $values = [];

    /**
     * @param  FieldValue  $value
     * @return $this
     */
    public function push(FieldValue $value): FieldValues
    {
        if (!$this->has($value)) {
            array_push($this->values, $value);
        }

        return $this;
    }

    /**
     * @param  FieldValue  $value
     * @return $this
     */
    public function unshift(FieldValue $value): FieldValues
    {
        if (!$this->has($value)) {
            array_unshift($this->values, $value);
        }

        return $this;
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->values = [];
    }

    /**
     * @param  FieldValue  $value
     * @return bool
     */
    protected function has(FieldValue $value): bool
    {
        $result = array_filter($this->values, function (FieldValue $val) use ($value) {
            return ($value->getId() === $val->getId());
        });

        return (count($result) > 0);
    }

    /**
     * @param  FieldValue  $value
     */
    protected function update(FieldValue $value): void
    {
        array_map(function (FieldValue $val) use ($value) {
            if ($value->getId() === $val->getId()) {
                $val->setValue($value->getValue());
            }
        }, $this->values);
    }

    /**
     * @param  array  $values
     */
    public function updateValues(array $values): void
    {
        array_map(function (array $value) {
            $this->update((new StringValue((int) $value['id'], (string) $value['value'])));
        }, $values);
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param  int  $id
     * @return StringValue|null
     */
    public function getValueById(int $id): ?StringValue
    {
        $result = array_filter($this->values, function (FieldValue $val) use ($id) {
            return ($id === $val->getId());
        });

        return (count($result)) ? current($result) : null;
    }
}
