<?php

namespace Dg482\Red\Adapters\Interfaces;

use Closure;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Model;

/**
 * Interface AdapterInterfaces
 * @package App\Admin\adapters\interfaces
 */
interface AdapterInterfaces
{
    /**
     * @return array
     */
    public function getTypeFields(): array;

    /**
     * Определение модели в контексте адаптера
     *
     * @param $model
     * @return $this
     */
    public function setModel($model): self;

    /**
     * Чтение данных из БД
     *
     * @param  int  $limit
     * @return array
     */
    public function read($limit = 1): array;

    /**
     * Запись данных в БД
     *
     * @return bool
     */
    public function write(): bool;

    /**
     * Удаление данных из БД
     *
     * @return bool
     */
    public function delete(): bool;

    /**
     * Обновение данных в БД
     *
     * @return bool
     */
    public function update(): bool;

    /**
     * Исолнение текущего запроса к БД
     *
     * @return bool
     */
    public function execute(): bool;

    /**
     * Возвращает описательный массив колонок таблицы модели в БД
     * [
     *   [
     *      "id" => "email",
     *      "table" => "users",
     *      "type" => "string"
     *  ],
     *  ....
     * ]
     *
     * @param  Model  $model
     * @param  array  $ignoreColumns
     * @return array
     */
    public function getTableColumns(Model $model, array $ignoreColumns = []): array;

    /**
     * Возвращает реализацию Field для отображения поля в UI
     *
     * @param  array  $columnMeta
     * @return Field
     */
    public function getTableField(array $columnMeta): Field;

    /**
     * @param  array  $filters
     * @return AdapterInterfaces
     */
    public function setFilters(array $filters): AdapterInterfaces;

    /**
     * Отношения в контексте модели адаптера
     * @return array
     */
    public function with(): array;
}
