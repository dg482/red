<?php

namespace Dg482\Red\Commands\Crud;

use Dg482\Red\Commands\Interfaces\CommandInterfaces;
use Dg482\Red\Model;

/**
 * Class Update
 * @package Dg482\Red\Commands\Crud
 */
class Update extends Command implements CommandInterfaces
{
    /** @var Model */
    private Model $model;

    /** @var array */
    private array $data = [];

    /** @var array */
    private array $relations = [];

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $model = $this->getModel();
        $request = $this->getData();

        if ($update = $model->updateModel($this->getData())) {
            $this->setModel($model);
        }

        return $update;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return Update
     */
    public function setModel(Model $model): Update
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Update
     */
    public function setData(array $data): Update
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param array $relations
     * @return Update
     */
    public function setRelations(array $relations): Update
    {
        $this->relations = $relations;

        return $this;
    }
}
