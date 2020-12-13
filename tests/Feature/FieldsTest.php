<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Fields\StringField;
use Dg482\Red\Tests\TestCase;
use Dg482\Red\Values\StringValue;

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
        /** @var StringField $field */
        $field = $this->fieldTest(StringField::class);

        $this->assertTrue('string' === $field->getFieldType());

        $field->setValue('test value');

        $this->assertInstanceOf(StringValue::class, $field->getValue());

        $this->assertEmpty($field->getValue()->getId());
        $this->assertTrue('test value' === $field->getValue()->getValue());

        // updated value object
        $field->getValue()
            ->setId(4)
            ->setValue('value test');

        $this->assertTrue(4 === $field->getValue()->getId());
        $this->assertTrue('value test' === $field->getValue()->getValue());
    }

    protected function fieldTest($fieldClass)
    {
        /** @var  $field */
        $field = (new $fieldClass)
            ->setAttributes([
                'readonly' => false,
            ])
            ->hideForm()
            ->hideTable()
            ->setName('Test Field')
            ->setField('email');


        $this->assertTrue('Test Field' === $field->getName());
        $this->assertFalse($field->isDisabled());
        $this->assertFalse($field->isMultiple());

        // set trigger to UI
        $field->setTrigger(['blur', 'change', 'click']);


        $this->assertTrue('blur|change|click' === $field->getTrigger());

        // translator
        $field->setErrorMessages([
            'validation.required' => 'Test Field is required',
        ]);

        $field->setTranslator(function ($key) use ($field) {
            $messages = $field->getErrorMessages();

            // return set message or trans helper framework
            return $messages[$key] ?? $key;
        });
        // set validation
        $field->setRequired()
            ->addValidators('in:test@mail.com')
            ->addValidators('email')
            ->addValidators('min:3')
            ->addValidators('max:80');

        $this->assertCount(5, $field->getValidators());

        $field->setData(['data' => 123]);

        $this->assertArrayHasKey('data', $field->getData());

        $formField = $field->getFormField();

        array_map(function () use ($formField) {
            $this->assertArrayHasKey('id', $formField);
        }, ['id', 'name', 'type', 'attributes', 'validators']);


        $field->setValidators([]);
        $field->setTranslator(null);
        $this->assertCount(0, $field->getValidators());

        $field->setRequired()
            ->setDisabled(true);

        $this->assertTrue($field->isDisabled());

        return $field;
    }
}
