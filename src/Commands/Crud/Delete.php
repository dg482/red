<?php

namespace Dg482\Red\Commands\Crud;

use Dg482\Red\Commands\Interfaces\CommandInterfaces;

/**
 * Class Delete
 * @package Dg482\Red\Commands\Crud
 */
class Delete extends Update implements CommandInterfaces
{
    /**
     * @return bool
     */
    public function execute(): bool
    {
        $model = $this->getModel();

        return ($model && method_exists($model, 'delete')) ? $model->delete() : false;
    }
}
