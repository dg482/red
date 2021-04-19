<?php

namespace Dg482\Red\Builders\Form\Fields;

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
}
