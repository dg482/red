<?php

namespace Dg482\Red\Resource;

use Dg482\Red\Interfaces\ResourceAssetsInterface;

/**
 * Class Assets
 * @package Dg482\Red\Resource
 */
class Assets implements ResourceAssetsInterface
{
    /**
     * @param  int  $id
     * @return ResourceAssetsInterface
     */
    public function get(int $id): ResourceAssetsInterface
    {
        return $this;
    }

    /**
     * @param  array  $parameter
     * @return bool
     */
    public function store(array $parameter): bool
    {
        return false;
    }

    /**
     * @param  string  $path
     * @return bool
     */
    public function has(string $path): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function remove(): bool
    {
        return false;
    }
}
