<?php

namespace Dg482\Red\Builders\Form\Fields;

use Dg482\Red\Builders\Form\Structure\BaseStructure;
use Dg482\Red\Builders\Form\Structure\Fieldset;

/**
 * Trait FieldTrait
 * @package Dg482\Red\Builders\Form\fields
 */
trait FieldTrait
{
    /**
     * @param string $name field label
     * @param string $modelName model field
     * @param array $attributes field attributes
     * @return Field|BaseStructure|Fieldset
     */
    public static function make(
        string $name,
        string $modelName,
        $attributes = [
            'showTable' => true,
            'showForm' => true
        ]
    ): Field {
        /** @var Field $field */
        $field = new self();

        $field->setName($name);
        $field->setField($modelName);
        $field->setAttributes($attributes);

        return $field;
    }
}
