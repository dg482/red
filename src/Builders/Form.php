<?php

namespace Dg482\Red\Builders;

use Dg482\Red\Adapters\Adapter;
use Dg482\Red\Model;

/**
 * Class Form
 * @package Dg482\Red\Builders
 */
class Form
{
    /** @var Model */
    private Model $model;

    /** @var Adapter */
    private Adapter $adapter;

    /**
     * Form constructor.
     * @param  Adapter  $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param  Model  $model
     * @return Form
     */
    public function setModel(Model $model): Form
    {
        $this->model = $model;

        return $this;
    }
}
