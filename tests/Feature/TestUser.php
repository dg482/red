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

    public function updateModel(array $attributes, array $options = []): bool
    {
        return false;
    }

    /**
     * @return string[]
     */
    public function getFieldsModel(): array
    {
        return ['id', 'email', 'name'];
    }

    /**
     * create model stub
     *
     * @param  array  $request
     * @return Model
     */
    public function create(array $request): Model
    {
        list($this->id, $this->email, $this->name) = $request;
        return $this;
    }

    /**
     *
     * @return string[]
     */
    public function getFillable()
    {
        return ['email', 'name'];
    }
}
