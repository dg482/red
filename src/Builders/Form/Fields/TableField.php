<?php

namespace Dg482\Red\Builders\Form\Fields;

use Dg482\Red\Exceptions\EmptyFieldNameException;

/**
 * Class TableField
 * @package Dg482\Red\Builders\Form\Fields
 */
class TableField extends Field
{

    /** @var string */
    const FIELD_TYPE = 'table';


    /**
     * @param  array  $value
     * @return TableField
     */
    public function setFieldValue(array $value): Field
    {
        $value = $value === [] ? [
            'columns' => [],
            'data' => [],
            'pagination' => [
                'total' => 0,
                'current' => 1,
                'last' => 1,
                'perPage' => 25,
            ],
        ] : $value;

        $this->value = $value;

        return $this;
    }

    /**
     * Массив параметров поля для отрисовки в UI
     * @param  bool  $isClientValidator
     * @return array
     * @throws EmptyFieldNameException
     */
    public function getFormField(bool $isClientValidator = false): array
    {
        return [
            'id' => empty($this->id) ? time() + rand(1, 99999) : $this->id,
            'name' => $this->getName(),
            'type' => $this->getFieldType(),
            'field' => $this->getField(),
            'disabled' => $this->isDisabled(),
            'attributes' => $this->getAttributes(),
            'validators' => [],
            'value' => $this->getValue(),
        ];
    }
}
