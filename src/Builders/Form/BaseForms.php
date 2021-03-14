<?php

namespace Dg482\Red\Builders\Form;

use Dg482\Red\Builders\Form\Buttons\Button;
use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use Exception;

/**
 * Class BaseForms
 * @package App\Models\Forms
 */
class BaseForms
{
    use ValidatorsTrait;

    /** @var string */
    protected const ACTION_SAVE = 'action_save';

    /** @var string */
    protected const ACTION_REPLICATE = 'action_replicate';

    /** @var string */
    protected const ACTION_CANCEL = 'action_cancel';

    /**
     * @var string $title
     */
    public string $title = 'forms';

    /** @var string */
    protected string $formName = 'ui';

    /** @var array */
    private array $actions = [];

    /** @var Resource */
    protected Resource $resource;

    /**
     * Текущая модель формы
     * @var Model
     */
    protected Model $model;

    /**
     * @return array
     * @throws Exception
     */
    protected function fields(): array
    {
        return $this->resource()
            ->setContext(BaseForms::class)
            ->fields();
    }

    /**
     * @return Resource
     */
    public function resource(): Resource
    {
        return $this->resource;
    }

    /**
     * @param  Model  $model
     * @return $this
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return BaseForms
     */
    public function getForm(): BaseForms
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getFormTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        if ($this->actions === []) {
            array_push($this->actions, (new Button)
                ->make('Сохранить')
                ->setAction(self::ACTION_SAVE)
                ->setType('primary')
                ->setIcon('check'));
            array_push($this->actions, (new Button)
                ->make('Копировать')
                ->setAction(self::ACTION_REPLICATE)
                ->setIcon('copy'));

            array_push($this->actions, (new Button)
                ->make('Отменить')
                ->setAction(self::ACTION_CANCEL)
                ->setType('danger')
                ->setIcon('close'));
        }

        return $this->actions;
    }

    /**
     * @param  array $actions
     * @return $this
     */
    public function setActions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormName(): string
    {
        return $this->formName;
    }
}
