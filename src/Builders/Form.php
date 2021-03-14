<?php

namespace Dg482\Red\Builders;

use Dg482\Red\Adapters\Adapter;
use Dg482\Red\Builders\Form\Fields\BigintField;
use Dg482\Red\Builders\Form\Fields\FloatField;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Fields\IntegerField;
use Dg482\Red\Builders\Form\Fields\SelectField;
use Dg482\Red\Builders\Form\Fields\SmallintField;
use Dg482\Red\Builders\Form\Fields\StringField;
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

    /**
     * @return array
     */
    public static function getSupportFieldsType(): array
    {
        return [
            StringField::getType(),
            SelectField::getType(),
            HiddenField::getType(),

            IntegerField::getType(),
            BigintField::getType(),
            SmallintField::getType(),
            FloatField::getType(),
        ];
    }
}
