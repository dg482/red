<?php

namespace Dg482\Red\Commands\Crud;

use Dg482\Red\Commands\Interfaces\CommandInterfaces;

/**
 * Class Read
 * @package Dg482\Red\Commands\Crud
 */
class Read extends Command implements CommandInterfaces
{
    /** @var int $perPage */
    protected int $perPage = 25;

    /**
     * @return bool
     */
    public function execute(): bool
    {
        if (empty($this->result)) {
            $this->setResult([]);// set result read cmd
        }

        return (!empty($this->result));
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     * @return $this
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }
}
