<?php

namespace Dg482\Red\Adapters;

use Closure;
use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Fields\SelectField;
use Dg482\Red\Builders\Form\Fields\StringField;
use Dg482\Red\Model;

/**
 * Class BaseAdapter
 * @package Dg482\Red\Adapters
 */
class BaseAdapter extends Adapter
{

    /**
     * @inheritDoc
     */
    public function setModel($model): Interfaces\AdapterInterfaces
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTableColumns(Model $model, array $ignoreColumns = []): array
    {
        return $this->tableColumns;
    }

    /**
     * @inheritDoc
     */
    public function setFilter(?Closure $filter): AdapterInterfaces
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTableField(array $columnMeta): Field
    {
        $targetClass = 'Dg482\\Red\\Builders\\Form\\Fields\\'.ucfirst($columnMeta['type']).'Field';

        if (class_exists($targetClass)) {
            $field = (new $targetClass);
        } else {
            $field = new StringField();
        }

        $field->setField($columnMeta['id']);

        return $field;
    }
}
