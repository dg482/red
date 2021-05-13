<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;

/**
 * Class TestUserForm
 * @package Dg482\Red\Tests\Feature
 */
class TestUserForm extends BaseForms
{

    public function __construct()
    {
        $this->model = new TestUser();
    }

    /**
     * @param  Field  $field
     * @param $value
     * @return StringValue
     */
    public function saveFieldEmail(Field $field, $value): StringValue
    {
        return new StringValue(0, 'test@extra.com');
    }

    /**
     * @param  Field  $field
     * @return Field
     */
    public function formFieldEmail(Field $field): Field
    {
        $field->setFilterText();
        $field->setFilterFn(function ($query, $request) {
            // $query->where('email', $request['email']);
            return $query;
        });

        return $field;
    }
}
