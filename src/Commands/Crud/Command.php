<?php

namespace Dg482\Red\Commands\Crud;

use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;

/**
 * Class Command
 * @package Dg482\Red\Commands\Crud
 */
abstract class Command
{
    /** @var array */
    protected array $result = [];

    /** @var AdapterInterfaces */
    protected AdapterInterfaces $adapter;

    /** @var bool */
    protected bool $multiple = false;

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param $result
     * @return $this
     */
    public function setResult($result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param  bool  $multiple
     * @return $this
     */
    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @return AdapterInterfaces
     */
    public function getAdapter(): AdapterInterfaces
    {
        return $this->adapter;
    }

    /**
     * @param  AdapterInterfaces  $adapter
     * @return $this
     */
    public function setAdapter(AdapterInterfaces $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }
}
