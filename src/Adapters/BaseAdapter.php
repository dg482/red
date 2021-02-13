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
        switch ($columnMeta['type']) {
            case StringField::getType():
            default:
                $field = new StringField();
                break;
            case HiddenField::getType():
                $field = new HiddenField();
                break;
            case SelectField::getType():
                $field = new SelectField();
                break;
        }

        $field->setField($columnMeta['id']);

        return $field;
    }
}
