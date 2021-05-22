<?php

namespace Dg482\Red\Builders\Form;

use Dg482\Red\Builders\Form\Buttons\Button;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Fields\StringField;
use Dg482\Red\Builders\Form\Structure\BaseStructure;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Dg482\Red\Interfaces\FormModelInterface;
use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use Exception;

/**
 * Class BaseForms
 * @package App\Models\Forms
 */
class BaseForms implements FormModelInterface
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
     * Структура формы (внешнее определение порядка следования/вложенности элементов)
     * @var array
     */
    private array $structure = [];

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
     * @param  Resource  $resource
     * @return $this
     */
    public function setResource(Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
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
     * @param  string  $title
     * @return BaseForms
     */
    public function setTitle(string $title): BaseForms
    {
        $this->title = $title;

        return $this;
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
     * @param  array  $actions
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

    /**
     * @param  string  $formName
     * @return $this
     */
    public function setFormName(string $formName): self
    {
        $this->formName = $formName;

        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function resourceFields(): array
    {
        return $this->fields();
    }

    /**
     * @param  Field  $field
     * @return Field
     * @throws EmptyFieldNameException
     */
    public function formFieldId(Field $field): Field
    {
        return (new HiddenField)
            ->setField($field->getField())
            ->setValue($field->getValue()->getValue())
            ->hideTable()
            ->hideForm();
    }

    /**
     * @param  array  $structure
     */
    public function setFormStructure(array $structure): void
    {
        $this->structure = $structure;
    }

    /**
     * @param  array  $newField
     * @return Field
     */
    public function createField(array $newField): Field
    {
        $field = new StringField();

        return $field;
    }

    /**
     * @param  array  $fields
     * @return array
     */
    public function sortFields(array $fields = []): array
    {
        if ($this->structure) {
            $extract = function ($field, &$result = []) use (&$extract) {
                $result[$field->getField()] = $field;
                if ($field instanceof BaseStructure) {
                    array_map(function (Field $field) use (&$result, &$extract) {
                        $extract($field, $result);
                    }, $field->getItems());
                }
            };
            $extractFields = [];
            foreach ($fields as $field) {
                $extract($field, $extractFields);
            }
            $result = [];
            $buildStructure = function (array $field, &$result = [], BaseStructure &$container = null) use (&$buildStructure, $extractFields) {
                $target = $extractFields[$field['field']] ?? null;
                if (!$target) {
                    $target = $this->createField($field);
                }

                if ($target instanceof BaseStructure) {
                    if (!empty($field['children'])) {
                        $target->setItems([]);// reset structure items
                        foreach ($field['children'] as $child) {
                            $buildStructure($child, $result, $target);
                        }
                    }
                } else {
                    if ($container instanceof BaseStructure) {
                        $container->pushItem($target);
                    }
                }
                if (null === $container) {
                    $result[] = $target;
                }
            };

            foreach ($this->structure as $item) {
                $buildStructure($item, $result);
            }
        }

        return $fields;
    }
}
