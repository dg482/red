<?php

namespace Dg482\Red\Resource;

use Dg482\Red\Adapters\Adapter;
use Dg482\Red\Adapters\BaseAdapter;
use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Red\Builders\Form;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Structure\BaseStructure;
use Dg482\Red\Builders\Form\Structure\Fieldset;
use Dg482\Red\Commands\Crud\Read;
use Dg482\Red\Model;
use Dg482\Red\Resource\Actions\Create as ActionCreate;
use Dg482\Red\Resource\Actions\Delete as ActionDelete;
use Dg482\Red\Resource\Actions\Update as ActionUpdate;
use Dg482\Red\Exceptions\EmptyFieldNameException;

/**
 * Ресурс модели
 *
 * Ресурс предоставляет общие методы для отображения моделей в табличном представление.
 *
 * В $rowActions и $actions определяются доступные действия в таблицах.
 *
 * Для отображения ресурса в форме редактирования используются формы Dg482\Red\Builder\Form с
 * ссылкой на ресурс. Форма определяет методы для работы с полями, сохранение и обновление данных через CRUD команды.
 *
 * Отношения моделей (relations) определяются как самостоятельные ресурсы не имеющие свои формы унаследованные от
 * RelationsResource для HasMany и RelationResource для HasOne типа отношения.
 *
 * @package Dg482\Red\resource
 */
class Resource
{
    /**
     * Кол-во элементов на страницу
     * @var int
     */
    protected const PAGE_SIZE = 50;

    /**
     * Адаптер для работы с БД
     * @var AdapterInterfaces
     */
    protected AdapterInterfaces $adapter;

    /**
     * Команда адаптера
     * @var string
     */
    protected string $command = Read::class;

    /**
     * Текущая модель ресурса
     * @var Model
     */
    protected Model $model;

    /**
     * Текущее отношение в контексте ресурса
     * @var Model|null
     */
    protected ?Model $relation = null;

    /**
     * Список доступных отношений, ссылки на RelationResource
     * @var array
     */
    protected array $relations = [];

    /**
     * Модель формы
     * @var BaseForms
     */
    protected BaseForms $formModel;

    /** @var string */
    protected string $context = '';

    /** @var array */
    protected array $validators = [];

    /**
     * Список доступных действий для таблицы
     * @var string[]
     */
    protected array $actions = [
        ActionCreate::class,
        ActionUpdate::class,
        ActionDelete::class,
    ];

    /**
     * Список доступных действий для каждой записи таблицы
     * @var string[]
     */
    protected array $rowActions = [
        ActionUpdate::class,
        ActionDelete::class,
    ];

    /**
     * Массив с значениями полей модели
     * @var array
     */
    public array $values = [];

    /**
     * Поля которые будут скрыты при построение списка полей
     * @var array $hidden_fields
     */
    protected array $hidden_fields = [];

    /**
     * Подписи к полям
     * @var array $labels
     */
    protected array $labels = [];

    /** @var array */
    protected array $tables = [];

    /**
     * Название ресурсы, используется в UI
     * @var string
     */
    protected string $title = '';

    /**
     * Resource constructor.
     * @param  Adapter  $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $adapter->setCommand((new $this->command));

        $this->setAdapter($adapter);

        $this->initResource(__CLASS__);
    }

    /**
     * @return AdapterInterfaces
     */
    public function getAdapter(): AdapterInterfaces
    {
        if (null === $this->adapter) {
            $this->setAdapter(new BaseAdapter());
        }

        return $this->adapter;
    }

    /**
     * @param  AdapterInterfaces  $adapter
     */
    public function setAdapter(AdapterInterfaces $adapter): void
    {
        $this->adapter = $adapter;
    }


    /**
     * Поля формы ресурса
     * @return array|array[]
     * @throws EmptyFieldNameException
     */
    public function formFields(): array
    {
        $fields = [Fieldset::make('Empty fieldset', '')];

        if (method_exists($this->model, 'resourceFields')) {
            $fields = $this->model->resourceFields();
        }

        return $this->filterFields('isShowForm', $fields);
    }

    /**
     * Фильтр полей
     * @param  string  $method
     * @param  array  $fields
     * @return array|array[]
     * @throws EmptyFieldNameException
     */
    protected function filterFields(string $method, array $fields): array
    {
        $original = (in_array($this->getContext(), [Form::class, FormsResource::class]));

        return array_map(function (Field $field) use ($original) {

            if (false === empty($field->getField())) {
                // set model field value
                $this->values[$field->getField()] = $field->getFieldValue($original);

                if ([] !== $field->getValidators()) {
                    $this->validators[$field->getField()] = $field->getValidators();
                }
            }

            return $field->getFormField();
        }, array_filter($fields, function (Field $field) use ($method) {
            if ($field instanceof BaseStructure) {
                $field->setItems($this->filterFields($method, $field->getItems()));
            }

            return $field->{$method}();
        }));
    }

    /**
     * Значения полей модели
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
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
     * @return Resource
     */
    public function setModel(Model $model): Resource
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param  null  $relation
     * @return Resource
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return array
     */
    public function getHiddenFields(): array
    {
        return $this->hidden_fields;
    }

    /**
     * @param  array  $hidden_fields
     * @return Resource
     */
    public function setHiddenFields(array $hidden_fields): Resource
    {
        $this->hidden_fields = $hidden_fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @param  array  $labels
     * @return Resource
     */
    public function setLabels(array $labels): Resource
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @param $values
     * @return $this
     */
    public function pushValues($values): self
    {
        array_push($this->values, $values);

        return $this;
    }

    /**
     * @param $values
     * @return $this
     */
    public function setValues($values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param  string[]  $actions
     * @return Resource
     */
    public function setActions(array $actions): Resource
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRowActions(): array
    {
        return $this->rowActions;
    }

    /**
     * @param  string[]  $rowActions
     * @return Resource
     */
    public function setRowActions(array $rowActions): Resource
    {
        $this->rowActions = $rowActions;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     * @return Resource
     */
    public function setTitle(string $title): Resource
    {
        $this->title = $title;

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
     * @param  array  $relations
     * @return Resource
     */
    public function setRelations(array $relations): Resource
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param  string  $context
     * @return Resource
     */
    public function setContext(string $context): Resource
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param  string  $context
     * @return Resource
     */
    public function initResource(string $context = ''): Resource
    {
        $this->setContext($context);

        return $this;
    }

    /**
     * @return Field[]
     * @throws \Exception
     */
    public function fields(): array
    {
        $validators = $this->formModel->getValidators();
        $error_message = $this->formModel->getErrorMessages();

        $fields = $this->adapter->getTableColumns($this->model, $this->hidden_fields);

        // 1.3 build Fields list
        $fields = array_map(function (array $columnMeta) {
            $id = $columnMeta['id'] ?? null;

            if (in_array($id, $this->hidden_fields)) {
                $columnMeta['type'] = HiddenField::getType();
            }

            $field = $this->adapter->getTableField($columnMeta);
            $field->setField($id);

            if (isset($this->labels[$id])) {
                $field->setName($this->labels[$id]);
            }

            return $field;
        }, $fields);

        return array_map(function (Field $field) use ($validators, $error_message) {

            $method = 'formField'.ucfirst($field->getField());
            $key = $field->getField();

            if (method_exists($this->formModel, $method)) {
                /** @var Field $field */
                $field = $this->formModel->{$method}($field);
                $field->setField($key);
                $field->setName($field->getName());
                //$newField->setAttributes($field->getAttributes());
            }

            if ($this->getRelation()) {
                $field->setField($this->getRelation().'|'.$key);  //set relation name
            }

            if (isset($validators[$key])) {
                array_map(function (string $rule) use (&$field, $error_message, $key) {
                    $idx = current(explode(':', $rule));
                    if (isset($error_message[$key][$idx])) {
                        $field->addValidators($rule, $error_message[$key][$idx], $idx);
                    } else {
                        $field->addValidators($rule, null, $idx);
                    }
                }, $validators[$key]);
            }

            return $field;
        }, $fields);
    }

    /**
     * @param  BaseForms  $form
     * @return Resource
     */
    public function setForm(BaseForms $form): Resource
    {
        $this->formModel = $form;// 1.1 init form

        $this->setModel($this->formModel->getModel());// 1.2 set form model


        $this->getAdapter()->setModel($this->getModel());// 1.3 set adapter model

        return $this;
    }
}
