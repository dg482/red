<?php

namespace Dg482\Red\Resource\Actions;

/**
 * Class Delete
 * @package Dg482\Red\Resource\Actions
 */
class Delete extends Crud
{
    /** @var string */
    protected string $action = 'delete';

    /** @var string */
    protected string $icon = 'delete';

    /** @var string */
    protected string $text = 'Удалить';

    /** @var bool */
    protected bool $confirm = true;
}
