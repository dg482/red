<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Adapters\BaseAdapter;
use Dg482\Red\Builders\Form\BaseForms;
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
        ]);

        $model = $this->createMock(Model::class);


        /** @var  BaseForms::class $baseForm */
        $baseForm = new BaseForms();

        // 1 configure form
        $baseForm->setModel($model);

        // 2.1 create resource, set default adapter
        $resource = new Resource($adapter);
        $resource->setTitle('Test Users');
        $resource->setModel($model);// 2.2 set Model
        $resource->setForm($baseForm);// 2.3 set Form

        $resource->setLabels([// 3.2 set labels
            'id' => '#',
            'email' => 'Email',
            'name' => 'Name',
        ]);

        $resource->setActions([ActionDelete::class]);

        $resource->setRowActions([ActionDelete::class]);

        $arResource = $resource->getTable();

        $this->assertEquals('Test Users', $arResource['title']);

        $this->assertCount(4, $arResource['columns']);
        $this->assertCount(1, $arResource['actions']);
    }
}