<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Adapters\BaseAdapter;
use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use Dg482\Red\Tests\TestCase;

/**
 * Class ResourceTest
 * @package Dg482\Red\Tests\Feature
 */
class ResourceTest extends TestCase
{
    /**
     *
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


        // 2.1 create resource, set default adapter
        $resource = new Resource($adapter);
        $resource->setModel($model);// 2.2 set Model
    }
}