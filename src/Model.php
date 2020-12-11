<?php

namespace Dg482\Red;

/**
 * Interface Model
 * @package Dg482\Mrd
 */
interface Model
{
    /**
     * Обертка над методом обновления в модели
     * @param $request
     * @return bool
     */
    public function update(array $request): bool;

    /**
     * Получить поля авто заполнения
     * @return array
     */
    public function getFields(): array;

    /**
     * Обертка над методом создания модели
     * @param array $request
     * @return Model
     */
    public function create(array $request): Model;
}
