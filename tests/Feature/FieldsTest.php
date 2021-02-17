<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Adapters\BaseAdapter;
use Dg482\Red\Builders\Form;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Fields\IntegerField;
use Dg482\Red\Builders\Form\Fields\SelectField;
use Dg482\Red\Builders\Form\Fields\StringField;
use Dg482\Red\Builders\Form\Fields\Values\FieldValues;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Dg482\Red\Exceptions\BadVariantKeyException;
use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
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

    /**
     * @throws BadVariantKeyException
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    public function testSelectField()
    {
        $_REQUEST['gender'] = 1;// Man

        /** @var SelectField $field */
        $field = $this->fieldTest(SelectField::class, 'gender');

        $field->addVariants([
            ['id' => 1, 'value' => 'Man'],
            ['id' => 2, 'value' => 'Woman'],
        ]);

        $this->assertTrue('Man' === (string) $field->getValue());

        $this->expectException(BadVariantKeyException::class);

        $field->addVariants([
            ['id' => null, 'value' => 'Bad variant'],
        ]);
    }

    /**
     * @throws BadVariantKeyException
     */
    public function testSelectFieldException()
    {
        /** @var SelectField $field */
        $field = $this->fieldTest(SelectField::class, 'gender');

        $this->expectException(BadVariantKeyException::class);

        $field->addVariants([
            ['_id' => 1, '_value' => 'Man'],
        ]);
    }

    /**
     * @throws BadVariantKeyException
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    public function testSelectFieldMultiple()
    {
        $_REQUEST['auto'] = [
            ['id' => 1, 'value' => 'Audi'],
            ['id' => 3, 'value' => 'MB'],// id variants
        ];

        /** @var SelectField $field */
        $field = (new SelectField)
            ->setField('auto')
            ->setMultiple(true)
            ->addVariants([
                ['id' => 1, 'value' => 'Audi'],
                ['id' => 2, 'value' => 'BMW'],
                ['id' => 3, 'value' => 'MB'],
            ]);

        $this->assertInstanceOf(FieldValues::class, $field->getValue());

        $this->assertCount(2, $field->getValue()->getValues());
    }

    public function testTextField()
    {
        /** @var StringField $field */
        $field = $this->fieldTest(StringField::class, 'email');

        $this->assertFalse($field->isMultiple());

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

        $_REQUEST['email'] = '192837465@test.com';

        $this->assertTrue($_REQUEST['email'] === (string) $field->getValue());
    }

    public function testTextFieldMultiple()
    {
        $_REQUEST['emails'] = [
            ['id' => 123, 'value' => '192837465@test.com'],
            ['id' => 321, 'value' => '564732891@com.test'],
        ];

        /** @var StringField $field */
        $field = $this->fieldTest(StringField::class, 'emails');
        $field->setMultiple(true);

        $this->assertInstanceOf(FieldValues::class, $field->getValue());

        $this->assertCount(2, $field->getValue()->getValues());

        $valueObj = $field->getValue();
        $valueObj->clear();
        $this->assertCount(0, $valueObj->getValues());

        $this->assertCount(2, $field->getValue()->getValues());

        $valueObj = $field->getValue();

        $valueObj->updateValues([
            ['id' => 123, 'value' => '564732891@com.test'],
            ['id' => 321, 'value' => '192837465@test.com'],
        ]);
        $value = $valueObj->getValueById(321);

        $this->assertInstanceOf(StringValue::class, $value);

        $this->assertTrue('192837465@test.com' === $value->getValue());
    }

    /**
     * @param $fieldClass
     * @param $name
     * @return mixed
     */
    protected function fieldTest($fieldClass, $name)
    {
        /** @var  $field */
        $field = (new $fieldClass)
            ->setField($name)// 1 set field
            ->setAttributes([
                'readonly' => false,
            ]) // 2 set attributes
            ->hideForm()// 2.1
            ->hideTable()// 2.2
            ->setName('Test Field');// 3 set field name (label)


        $this->assertTrue('Test Field' === $field->getName());
        $this->assertFalse($field->isDisabled());

        // 4 set trigger to UI
        $field->setTrigger(['blur', 'change', 'click']);


        $this->assertTrue('blur|change|click' === $field->getTrigger());

        // 5 set translator to validate message
        $field->setErrorMessages([
            'validation.required' => 'Test Field is required',
        ]);

        $field->setTranslator(function ($key) use ($field) {
            $messages = $field->getErrorMessages();

            // return set message or trans helper framework
            return $messages[$key] ?? $key;
        });
        // 5.1 set validation rules
        $field->setRequired()
            ->addValidators('in:test@mail.com')
            ->addValidators('email')
            ->addValidators('min:3')
            ->addValidators('max:80');

        $this->assertCount(5, $field->getValidators());

        // 6 set sample data
        $field->setData(['data' => 123]);

        $this->assertArrayHasKey('data', $field->getData());

        $formField = $field->getFormField();

        array_map(function () use ($formField) {
            $this->assertArrayHasKey('id', $formField);
        }, ['id', 'name', 'type', 'attributes', 'validators']);


        $field->setValidators([]);// clear validators
        $field->setTranslator(null);
        $this->assertCount(0, $field->getValidators());

        $field->setRequired()
            ->setDisabled(true);

        $this->assertTrue($field->isDisabled());

        return $field;
    }


    public function testFieldsTypesExists()
    {
        array_map(function (string $type) {
            $targetClass = 'Dg482\\Red\\Builders\\Form\\Fields\\'.ucfirst($type).'Field';
            $this->assertTrue(class_exists($targetClass));
        }, Form::getSupportFieldsType());
    }

    public function testFormFields()
    {
        $adapter = new BaseAdapter();//$this->createMock(BaseAdapter::class);
        $adapter->setTableColumns([
            ['id' => 'id', 'type' => 'int', 'table' => 'test'],
            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
            ['id' => 'name', 'type' => 'string', 'table' => 'test'],
            ['id' => 'password', 'type' => 'string', 'table' => 'test'],
            ['id' => 'age', 'type' => 'smallint', 'table' => 'test'],
            ['id' => 'balance', 'type' => 'float', 'table' => 'test'],
        ]);
//        $adapter->method('getTableColumns')->willReturn([
//            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
//        ]);


        $model = $this->createMock(Model::class);

        $baseForm = $this->getMockBuilder(BaseForms::class)
            ->setMethods(['formFieldEmail'])
            ->getMock();

        // 1 configure form
        $baseForm->setModel($model);

        $baseForm->setValidators([// 1.2 set validate field values
            'email' => ['required', 'email'],
            'name' => ['required', 'max:60'],
            'password' => ['required', 'min:6'],
        ]);

        $baseForm->setErrorMessages([// 1.3 set error messages
            'email' => [//<-- field name
                'email' => 'Поле Email заполненно не корректно!',//<-- validator name
            ],
        ]);

        $baseForm->method('formFieldEmail')->willReturn((new StringField));

        // 2.1 create resource, set default adapter
        $resource = new Resource($adapter);
        $resource->setModel($model);// 2.2 set Model
        $resource->setForm($baseForm);// 2.3 set Form

        // 3 configure resource
        $resource->setHiddenFields([
            'id',
        ]);// 3.1 set hidden fields

        $resource->setLabels([// 3.2 set labels
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
        ]);


        $fields = $resource->fields();

        foreach ($fields as $field) {
            $validators = $field->getValidators();

            switch ($field->getField()) {
                case 'id':
                    $this->assertInstanceOf(HiddenField::class, $field);
                    break;
                case 'email':
                    array_map(function ($validator) {
                        if ($validator['rule'] === 'email') {
                            $this->assertEquals($validator['message'], 'Поле Email заполненно не корректно!');
                        }
                    }, $validators);
                    $this->assertInstanceOf(StringField::class, $field);
                    $this->assertCount(2, $validators);
                    break;
                case 'name':
                case 'password':
                    $this->assertInstanceOf(StringField::class, $field);
                    $this->assertCount(2, $validators);
                    break;
                case 'age':
//                case 'balance':
                    $this->assertInstanceOf(IntegerField::class, $field);
                    break;
                default:
                    break;
            }
        }

        $jsonForm = $resource->getForm();
//        var_dump($jsonForm);

        $this->assertTrue($jsonForm['form'] === 'ui');

        $this->assertCount(6, $jsonForm['items']);
    }
}
