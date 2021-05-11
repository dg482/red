<?php

namespace Dg482\Red\Adapters;

use Dg482\Red\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Red\Builders\Form;
use Dg482\Red\Commands\Crud\Command;
use Dg482\Red\Commands\Interfaces\CommandInterfaces;
use Dg482\Red\Model;

/**
 * Class Adapter
 * @package App\Admin\adapters
 */
abstract class Adapter implements AdapterInterfaces
{
    /** @var CommandInterfaces|Command */
    private $command;

    /** @var array */
    protected array $typeFields = [];

    /** @var array */
    protected array $tableColumns = [];

    /** @var array */
    protected array $with = [];

    /**
     * @return array
     */
    public function getTypeFields(): array
    {
        $result = [];

        array_map(function (string $type) use (&$result) {
            $targetClass = 'Dg482\\Red\\Builders\\Form\\Fields\\'.ucfirst($type).'Field';
            if (class_exists($targetClass)) {
                $result[$type] = $targetClass;
            }
        }, Form::getSupportFieldsType());

        return $result;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        return $this->command->execute();
    }

    /**
     * @return Command|CommandInterfaces
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param  CommandInterfaces  $cmd
     */
    public function setCommand(CommandInterfaces $cmd): void
    {
        $this->command = $cmd;
    }

    /**
     * @return bool
     */
    public function write(): bool
    {
        return $this->write();
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function update(): bool
    {
        return false;
    }

    /**
     * @param  Model  $model
     * @param  array  $ignoreColumns
     * @return array
     */
    public function getTableColumns(Model $model, array $ignoreColumns = []): array
    {
        return $this->tableColumns;
    }

    /**
     * @param  array  $tableColumns
     * @return Adapter
     */
    public function setTableColumns(array $tableColumns): Adapter
    {
        $this->tableColumns = $tableColumns;

        return $this;
    }

    /**
     * @param  array  $with
     */
    public function setWith(array $with): void
    {
        $this->with = $with;
    }

    /**
     * @return array
     */
    public function with(): array
    {
        return $this->with;
    }
}
