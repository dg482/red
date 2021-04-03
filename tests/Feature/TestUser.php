<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Model;

/**
 * Class TestUser
 * @package Dg482\Red\Tests\Feature
 */
class TestUser implements Model
{
    /** @var int */
    public int $id;

    /** @var string */
    public string $email;

    /** @var string */
    public string $name;

    public function update(array $attributes, array $options = [])
    {
        return false;
    }

    public function getFields(): array
    {
        return ['id', 'email', 'name'];
    }

    public function create(array $request): Model
    {
        list($this->id, $this->email, $this->name) = $request;
        return $this;
    }

    public function getFillable()
    {
        return ['email', 'name'];
    }
}
