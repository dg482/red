<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Adapters\BaseAdapter;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\StringField;
use Dg482\Red\Commands\Crud\Create;
use Dg482\Red\Commands\Crud\Update;
use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use Dg482\Red\Tests\TestCase;
use Exception;
use Dg482\Red\Resource\Actions\Delete as ActionDelete;

/**
 * Class ResourceTest
 * @package Dg482\Red\Tests\Feature
 */
class ResourceTest extends TestCase
{
    public function testEmptyResource()
    {
        $adapter = new BaseAdapter();//$this->createMock(BaseAdapter::class);
        $adapter->setTableColumns([]);

        $model = $this->createMock(Model::class);

        /** @var  BaseForms::class $baseForm */
        $baseForm = new BaseForms();

        // 1 configure form
        $baseForm->setModel($model)
            ->setFormName('user_edit')
            ->setTitle('Edit test user');

        // 2.1 create resource, set default adapter
        $resource = new Resource($adapter);
        $resource->setTitle('Test Users');
        $resource->setIcon('users');
        $resource->setModel($model);// 2.2 set Model
        $resource->setForm($baseForm);// 2.3 set Form

        $fields = [
            (new StringField)->setField('test')->setName('Test'),
        ];

        $resource->setFields($fields);

        $arForm = $resource->getForm();

        $this->assertCount(1, $arForm['items']);
        $this->assertEquals('test',$arForm['items'][0]['field']);
    }

    /**
     * @throws Exception
     */
    public function testResource()
    {
        $adapter = new BaseAdapter();//$this->createMock(BaseAdapter::class);
        $adapter->setTableColumns([
            ['id' => 'id', 'type' => 'int', 'table' => 'test'],
            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
            ['id' => 'name', 'type' => 'string', 'table' => 'test'],
            ['id' => 'name', 'type' => 'password', 'table' => 'test'],
        ]);

        $model = $this->createMock(Model::class);

        /** @var  BaseForms::class $baseForm */
        $baseForm = new BaseForms();

        // 1 configure form
        $baseForm->setModel($model)
            ->setFormName('user_edit')
            ->setTitle('Edit test user');

        // 2.1 create resource, set default adapter
        $resource = new Resource($adapter);
        $resource->setTitle('Test Users');
        $resource->setIcon('users');
        $resource->setModel($model);// 2.2 set Model
        $resource->setForm($baseForm);// 2.3 set Form

        $resource->setLabels([// 3.2 set labels
            'id' => '#',
            'email' => 'Email',
            'name' => 'Name',
        ]);

        $resource->setActions([ActionDelete::class]);

        $resource->setRowActions([ActionDelete::class]);

        // set adapter result
        $resource->getAdapter()->getCommand()->setResult([
            (new TestUser)->create([1, 'example@domain.com', 'Test User One']),
            (new TestUser)->create([2, 'example@domain.com', 'Test User Two']),
            (new TestUser)->create([3, 'example@domain.com', 'Test User Three']),
            (new TestUser)->create([4, 'example@domain.com', 'Test User Four']),
        ]);

        $arResource = $resource->getTable();

        $this->assertEquals('Test Users', $arResource['title']);
        $this->assertEquals('users', $resource->getIcon());
        $this->assertCount(4, $arResource['columns']);
        $this->assertCount(1, $arResource['actions']);
        $this->assertCount(4, $arResource['data']);


        $arForm = $resource->getForm();

        $this->assertEquals('user_edit', $arForm['form']);
        $this->assertEquals('Edit test user', $arForm['title']);
    }

    public function testSaveResource()
    {
        $adapter = new BaseAdapter();//$this->createMock(BaseAdapter::class);
        $adapter->setTableColumns([
            ['id' => 'id', 'type' => 'int', 'table' => 'test'],
            ['id' => 'email', 'type' => 'string', 'table' => 'test'],
            ['id' => 'name', 'type' => 'string', 'table' => 'test'],
        ]);

        $model = $this->createMock(Model::class);

        /** @var  BaseForms::class $baseForm */
        $baseForm = new TestUserForm();


        // 1 configure form
        $baseForm->setModel($model)
            ->setFormName('user_edit');

        // 2.1 create resource, set default adapter
        $resource = new TestUserResource($adapter);
        $resource->setModel($model);

        $request = ['id' => 1, 'email' => 'test@mail.com'];

        $request = $resource->getFieldsValue($request);

        $this->assertEquals('test@extra.com', $request['email']);

        $command = $resource->getActionCommand($request);

        $this->assertInstanceOf(Update::class, $command);

        unset($request['id']);

        $command = $resource->getActionCommand($request);

        $this->assertInstanceOf(Create::class, $command);
    }
}
