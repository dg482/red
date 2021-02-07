<?php

namespace Dg482\Red\Adapters;

use Closure;
use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Red\Builders\Form\Fields\Field;
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
        return [];
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
        return new StringField();
    }

//    public function getTableFields(Model $model): array
//    {
//        return [];
//    }
}
