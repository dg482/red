<?php

namespace Dg482\Red\Resource\Actions;

/**
 * Class Create
 * @package Dg482\Red\Resource\Actions
 */
class Create extends Crud
{
    /** @var string */
    protected string $action = 'create';

    /** @var string */
    protected string $icon = 'plus';

    /** @var string */
    protected string $text = 'Создать';
}
