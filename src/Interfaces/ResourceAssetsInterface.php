<?php

namespace Dg482\Red\Interfaces;

/**
 * Interface ResourceAssetsInterface
 * @package Dg482\Red\Interfaces
 */
interface ResourceAssetsInterface
{
    /**
     * @param  int  $id
     * @return ResourceAssetsInterface
     */
    public function get(int $id): ResourceAssetsInterface;

    /**
     * @param  array  $parameter
     * @return bool
     */
    public function store(array $parameter): bool;

    /**
     * @param  string  $path
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @return bool
     */
    public function remove(): bool;
}
