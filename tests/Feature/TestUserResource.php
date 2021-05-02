<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Resource\Resource;

/**
 * Class TestUserResource
 * @package Dg482\Red\Tests\Feature
 */
class TestUserResource extends Resource
{
    /**
     * @var string
     */
    protected string $resourceModel = TestUser::class;


    /**
     * Return form model
     *
     * @return BaseForms
     */
    public function getFormModel(): BaseForms
    {
        if (!$this->formModel) {
            $this->setForm(new TestUserForm());
        }

        return parent::getFormModel();
    }
}
