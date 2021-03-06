<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Adapters\BaseAdapter;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Buttons\Button;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Fields\IntegerField;
use Dg482\Red\Builders\Form\Fields\StringField;
use Dg482\Red\Builders\Form\Fields\SwitchField;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use Dg482\Red\Tests\TestCase;
use Exception;

/**
 * Class FormTest
 * @package Dg482\Red\Tests\Feature
 */
class FormTest extends TestCase
{
    /**
     * @throws EmptyFieldNameException
     * @throws Exception
     */
    public function testFormFields()
    {
        $adapter = new BaseAdapter();//$this->createMock(BaseAdapter::class);
        $adapter->setTableColumns([
            ['id' => 'id', 'type' => 'integer', 'table' => 'test'],
            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
            ['id' => 'name', 'type' => 'string', 'table' => 'test'],
            ['id' => 'password', 'type' => 'string', 'table' => 'test'],
            ['id' => 'age', 'type' => 'smallint', 'table' => 'test'],
            ['id' => 'balance', 'type' => 'float', 'table' => 'test'],
            ['id' => 'total', 'type' => 'bigint', 'table' => 'test'],
            ['id' => 'dob', 'type' => 'date', 'table' => 'test'],
            ['id' => 'active', 'type' => 'switch', 'table' => 'test'],
            ['id' => 'last_time', 'type' => 'datetime', 'table' => 'test'],
            ['id' => 'switch', 'type' => 'switch', 'table' => 'test'],
        ]);
//        $adapter->method('getTableColumns')->willReturn([
//            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
//        ]);


        $model = $this->createMock(Model::class);

        /** @var  BaseForms::class $baseForm */
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
                            $this->assertEquals('Поле Email заполненно не корректно!', $validator['message']);
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
                case 'balance':
                    $this->assertInstanceOf(IntegerField::class, $field);
                    break;
                default:
                    break;
            }
        }

        $resource->getFormModel()->setFormStructure([
            ['field' => 'id'],
            ['field' => 'balance'],
            ['field' => 'total'],
            ['field' => 'active'],
            ['field' => 'email'],
            ['field' => 'name'],
            ['field' => 'dob'],
            ['field' => 'password'],
            ['field' => 'age'],
            ['field' => 'last_time'],
            ['field' => 'switch'],
        ]);


        $jsonForm = $resource->getForm();

        $this->assertTrue($jsonForm['form'] === 'ui');

        $this->assertCount(11, $jsonForm['items']);
    }

    public function testSwitchField()
    {
        $field = new SwitchField();
        $valueDisabled = $field->setField('switch')->disable()->getValue();
        $this->assertTrue('' === $valueDisabled->getValue());
        $this->assertTrue(-1 === $valueDisabled->getId());

        $enableValue = $field->enable()->getValue();
        $this->assertTrue('true' === $enableValue->getValue());
        $this->assertTrue(1 === $enableValue->getId());

        $field->setAction('user/off');
        $this->assertEquals('user/off', $field->getAction());
    }

    /**
     * @throws Exception
     */
    public function testFormActions()
    {
        $adapter = new BaseAdapter();//$this->createMock(BaseAdapter::class);
        $adapter->setTableColumns([
            ['id' => 'id', 'type' => 'int', 'table' => 'test'],
            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
            ['id' => 'name', 'type' => 'string', 'table' => 'test'],
        ]);

        $model = $this->createMock(Model::class);

        /** @var  BaseForms::class $baseForm */
        $baseForm = new BaseForms();

        // 1 configure form
        $baseForm->setModel($model);

        // 2.1 create resource, set default adapter
        $resource = new Resource($adapter);
        $resource->setModel($model);// 2.2 set Model
        $resource->setForm($baseForm);// 2.3 set Form

        $resource->getFormModel()->setActions([
            (new Button)
                ->make('Download XLS')
                ->setAction('download_xls')
                ->setType('primary')
                ->setIcon('xls'),
            (new Button)
                ->make('Download DOC')
                ->setAction('download_doc')
                ->setCommand(TestCommand::class)
                ->setBlock(true)
                ->setShape('rounded')
                ->setType('default')
                ->setIcon('xls'),
        ]);

        $this->assertCount(2, $resource->getFormModel()->getActions());
        $jsonForm = $resource->getForm();

        $this->assertEquals('Download XLS', $jsonForm['actions'][0]['text']);

        $this->assertEquals('Download DOC', $jsonForm['actions'][1]['text']);
        $this->assertEquals('rounded', $jsonForm['actions'][1]['shape']);
    }
}
