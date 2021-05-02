<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Commands\Crud\Update;

/**
 * Class TestCommand
 * @package Dg482\Red\Tests\Feature
 */
class TestCommand extends Update
{
    /**
     * @return bool
     */
    public function execute(): bool
    {
        $model = $this->getModel();
        $request = $this->getData();

        return false;
    }
}
