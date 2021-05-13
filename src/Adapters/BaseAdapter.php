<?php

namespace Dg482\Red\Adapters;

use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\StringField;
use Dg482\Red\Commands\Crud\Read;
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
    public function setFilters(array $filters): AdapterInterfaces
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTableField(array $columnMeta): Field
    {
        $targetClass = 'Dg482\\Red\\Builders\\Form\\Fields\\' . ucfirst($columnMeta['type']) . 'Field';

        if (class_exists($targetClass)) {
            $field = (new $targetClass);
        } else {
//            var_dump($targetClass);
            $field = new StringField();
        }

        $field->setField($columnMeta['id']);

        return $field;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function read($limit = 1): array
    {
        $cmd = $this->getCommand() ?? (new Read());

        $cmd->setAdapter($this)
            ->setMultiple($limit > 1)
            ->setPerPage($limit);

        $this->setCommand($cmd);

        $this->execute();

        return $this->getCommand()->getResult();
    }
}
