<?php

namespace Dg482\Red\Builders\Form\Structure;

use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Exceptions\EmptyFieldNameException;

/**
 * Class BaseStructure
 * @package App\Admin\Builder\Form\Structure
 */
abstract class BaseStructure extends Field
{
    /** @var array $items */
    protected array $items = [];

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param  array  $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param  Field  $field
     * @return $this
     */
    public function pushItem(Field $field): self
    {
        array_push($this->items, $field);

        return $this;
    }

    /**
     * @param  Field  $field
     * @return $this
     */
    public function unshiftItem(Field $field): self
    {
        array_unshift($this->items, $field);

        return $this;
    }


    /**
     * Массив параметров поля для отрисовки в UI
     * @return array
     * @throws EmptyFieldNameException
     */
    public function getFormField(): array
    {
        $formField = parent::getFormField();
        $formField['items'] = array_map(function (Field $field) {
            return $field->getFormField();
        }, $this->getItems());

        return $formField;
    }
}
