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
    public function update(array $attributes, array $options = []);

    /**
     * Получить поля авто заполнения
     * @return array
     */
    public function getFields(): array;

    /**
     * Массив полей автозаполнения
     * @return array
     */
    public function getFillable();
}
