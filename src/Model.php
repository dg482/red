<?php

namespace Dg482\Red;

/**
 * Interface Model
 * @package Dg482\Red
 *
 * @property int $id
 */
interface Model
{
    /**
     * Обертка над методом обновления в модели
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function updateModel(array $attributes, array $options = []): bool;

    /**
     * Массив полей автозаполнения
     * @return array
     */
    public function getFillable();
}
