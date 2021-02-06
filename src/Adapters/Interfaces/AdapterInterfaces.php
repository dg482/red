<?php

namespace Dg482\Red\Adapters\Interfaces;

use Closure;
use Dg482\Red\Model;

/**
 * Interface AdapterInterfaces
 * @package App\Admin\adapters\interfaces
 */
interface AdapterInterfaces
{
    /**
     * @param $model
     * @return $this
     */
    public function setModel($model): self;

    /**
     * @param  int  $limit
     * @return array
     */
    public function read($limit = 1): array;

    /**
     * @return bool
     */
    public function write(): bool;

    /**
     * @return bool
     */
    public function delete(): bool;

    /**
     * @return bool
     */
    public function update(): bool;

    /**
     * @return bool
     */
    public function execute(): bool;

    /**
     * @param  Model  $model
     * @return array
     */
    public function getTableFields(Model $model): array;

    /**
     * @param  Closure|null  $filter
     * @return AdapterInterfaces
     */
    public function setFilter(?Closure $filter): AdapterInterfaces;
}
