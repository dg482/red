<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Fields\StringField;
use Dg482\Red\Tests\TestCase;

/**
 * Class FieldsTest
 * @package Dg482\Red\Tests\Feature
 */
class FieldsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testTextField()
    {

        $field = (new StringField)
            ->hideForm()
            ->hideTable()
            ->setName('Test Field')
            ->setRequired();

        $this->assertTrue('Test Field' === $field->getName());
        $this->assertFalse($field->isDisabled());
        $this->assertFalse($field->isMultiple());
    }
}
