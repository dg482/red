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
     * Обертка над методом сохранения новой модели
     * @param  array  $attributes
     * @param  array  $options
     * @return Model
     */
    public function storeModel(array $attributes, array $options = []): Model;

    /**
     * Массив полей автозаполнения
     * @return array
     */
    public function getFillable();
}
