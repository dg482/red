<?php

namespace Dg482\Red\Builders;

use Closure;
use Dg482\Red\Adapters\Adapter;
use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\FileField;
use Dg482\Red\Model;
use Dg482\Red\Resource\Actions\ResourceAction;
use Dg482\Red\Resource\RelationResource;
use Dg482\Red\Resource\Resource;
use Exception;
use IteratorAggregate;

/**
 * Trait TableTrait
 * @package Dg482\Mrd\Builder\table
 */
trait TableTrait
{
    /**
     * Адаптер для работы с БД
     * @var AdapterInterfaces|Adapter
     */
    protected AdapterInterfaces $adapter;

    /** @var string */
    protected string $context = '';

    /** @var array */
    protected array $tables = [];

    /** @var array */
    protected array $labels = [];

    /**
     * Текущая модель
     * @var Model
     */
    protected Model $model;

    /**
     * Таблица
     *
     * @param  bool  $hardLoad
     * @return array
     * @throws Exception
     */
    public function getTable(bool $hardLoad = false): array
    {
        $this->setContext(Resource::class);

        $cls = get_class($this->getModel());

        if (isset($this->tables[$cls]) && $hardLoad === false) {
            return $this->tables[$cls];
        }

        /** @var Adapter $adapter */
        $adapter = $this->getAdapter();
        $adapter->setModel(new $this->model);

        $this->setFilters($adapter);

        /** @var array */
        $collection = ($this instanceof RelationResource) ? $this->getCollection()
            : $adapter->read($this->getPageSize());

        $paginator = [
            'items' => $adapter->getCommand()->getResult() ?? [],
            'total' => $collection['total'] ?? 0,
            'perPage' => $this->getPageSize(),
        ];

        // items table
        $items = [];

        $columns = $this->getFieldsTable();

        // columns table
        $setColumns = $this->getColumns();

        $relations = [];

        // колонка с действиями
        array_push($setColumns, [
            'width' => 100,
            'fixed' => 'right',
            'align' => 'center',
            'key' => 'action',
            'ellipsis' => true,
            'scopedSlots' => [
                'customRender' => 'action',
            ],
        ]);


        // формирование основных данных по модели
        array_map(function (Model $item) use (&$items, $columns, $relations) {
            $resultItem = ['id' => $item->id];
            array_map(function (Field $field) use ($item, &$resultItem) {
                $id = $field->getField();
                $relationSeparator = (!$this instanceof RelationResource) ? strpos($id, '@') : false;
                $printMethod = (method_exists($field, 'getPrintValue'));
                if ($relationSeparator !== false) {
                    list($relation, $field) = explode('@', $id);
                    if (!empty($item->{$relation}->{$field})) {
                        $field->setValue($item->{$relation}->{$field});
                    }
                } else {
                    $field->setValue($item->{$id} ?? '');
                }

                if ($printMethod) {
                    $resultItem[$id] = $field->getPrintValue();
                } else {
                    $resultItem[$id] = $field->isMultiple() ? $field->getValue()->getValues() :
                        $field->getValue()->getValue();
                }
            }, $columns);

            //формирование данных по отношениям модели
            array_map(function ($fields, string $relation) use ($item, &$resultItem) {
                $relationModel = $item->{$relation};
                if ($relationModel) {
                    array_map(function (Field $field) use ($item, $relation, $relationModel, &$resultItem) {
                        $id = str_replace(['@', $relation], '', $field->getField());
                        if ($relationModel instanceof IteratorAggregate) {
//                            $field->setFieldValue($relationModel);
                        } else {
                            $relationFieldMethod = $id;// $this->camel($id);

                            if (method_exists($relationModel, $relationFieldMethod)) {
                                $field->setFieldRelation($relationModel, $relationModel->{$relationFieldMethod});
                            }
//                            $field->setFieldValue($relationModel->{$id});
                        }
                        $resultItem[$field->getField()] = $field->getFieldValue();
                    }, $fields);
                }
            }, $relations, array_keys($relations));

            array_push($items, $resultItem);
        }, $paginator['items']);

        $result = [
            'title' => $this->getTitle(),
            'columns' => $setColumns,
            'data' => $items,
            'actions' => $this->getActionList($this->getActions()),
            'rowActions' => $this->getActionList($this->getRowActions()),
            'pagination' => [
                'defaultPageSize' => self::PAGE_SIZE,
                'total' => $paginator['total'],
            ],
        ];

        if ($this instanceof RelationResource) {
            $this->setValues($result);
        }

        $this->tables[$cls] = $result;

        return $result;
    }

    /**
     * @param  array  $actions
     * @return array
     */
    protected function getActionList(array $actions): array
    {
        $setActions = [];
        array_map(function (string $action) use (&$setActions) {
            /** @var ResourceAction $action */
            $action = (new $action);

            $formModel = $this->getFormModel();

            if ($formModel) {
                $action->setForm($formModel->getFormName());
            }

            array_push($setActions, [
                'id' => $action->getAction(),
                'text' => $action->getText(),
                'icon' => $action->getIcon(),
                'url' => $action->getActionUrl(),
                'form' => $action->getForm(),
                'confirm' => ($action->isConfirm()) ? $action->getConfirm() : false,
            ]);
        }, $actions);

        return $setActions;
    }

    /**
     * @param $paginator
     * @return array
     */
    protected function getPagination($paginator): array
    {
        $paginator['perPage'] = $paginator['perPage'] ?? 10;

        return [
            'showSizeChanger' => true,
            'total' => $paginator['total'] ?? 0,
            'currentPage' => $paginator['current'] ?? 1,
            'last' => ($paginator['total'] > 0) ? ceil($paginator['total'] / $paginator['perPage']) : 1,
            'perPage' => $paginator['perPage'],
        ];
    }

    /**
     * @param $id
     * @param $field
     * @return array
     */
    protected function buildColumn($id, Field $field): array
    {
        $fieldId = $field->id ?? $id;
        $column = [
            'id' => $fieldId,
            'key' => $fieldId,
            'name' => $field->getName() ?? $fieldId,
            'type' => $field->getFieldType(),
            'dataIndex' => $id,
            'ellipsis' => true,
            'width' => $field->getAttributeWidth() ?? $id === 'id' ? 80 : 200,
            'title' => $field->getName() ?? $this->labels[$id] ?? $fieldId,
        ];

        switch ($field->getFieldType()) {
            case FileField::FIELD_TYPE:
                $column['scopedSlots'] = [
                    'customRender' => 'file',
                ];
                break;
            default:
                break;
        }

        if ($field->getScopedSlots()) {
            $column['scopedSlots'] = $field->getScopedSlots();
        }

        if (!empty($field->getFilter())) {
            $column['filters'] = $field->getFilter();
        }
        $column['filterMultiple'] = ($field->isMultiple() || $field->isFilterMultiple());

        return $column;
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        // columns table
        $setColumns = [];

        array_map(function (Field $field) use (&$setColumns) {
            if ($field->isShowTable()) {
                array_push($setColumns, $this->buildColumn($field->getField(), $field));
            }
        }, $this->fieldsToArray());

        return $setColumns;
    }

    /**
     * @return array
     */
    private function fieldsToArray(): array
    {
        $fields = [];
        array_map(function (Field $field) use (&$fields) {
            $this->extractFields($field, $fields);
        }, $this->formModel->resourceFields());

        return $fields;
    }

    /**
     * @return array
     */
    protected function getFieldsTable(): array
    {
        return array_filter($this->fields(), function (Field $field) {
            return $field->isShowTable();
        });
    }

    /**
     * @return int
     */
    private function getPageSize(): int
    {
        return $_REQUEST['limit'] ?? 25;
    }

    /**
     * @param  Adapter  $adapter
     */
    private function setFilters(Adapter &$adapter): void
    {
        $setFilterFn = [];
        /** @var Field $field */
        foreach ($this->fieldsToArray() as $field) {
            $filter = ($field->isShowTable() && $field->getFilterFn() instanceof Closure)
                ? $field->getFilterFn() : null;
            if (!empty($filter)) {
                $setFilterFn[] = $filter;
            }
        }

        $adapter->setFilters($setFilterFn);
    }
}
