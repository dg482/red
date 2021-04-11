<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Builders\Menu\Frontend;
use Dg482\Red\Builders\Menu\MenuItem;
use Dg482\Red\Tests\TestCase;

/**
 * Class MenuTest
 * @package Dg482\Red\Tests\Feature
 */
class MenuTest extends TestCase
{
    public function testMenu()
    {
        $menu = (new Frontend)
            ->setMenu((new MenuItem)
                ->setName('schedule')
                ->setTitle('Расписание')
                ->setIcon('pe-7s-clock')
                ->setHref('/calendar')
                ->setRedirect('/dashboard/calendar')
                ->setBadge([
                    'text' => 12,
                    'class' => 'vsm--badge badge badge-push badge-danger',
                ]))
            ->setMenu((new MenuItem)
                ->setTitle('Администрирование')
                ->setIcon('pe-7s-server')
                ->addChild('Сотрудники', '/company/employees')
                ->addChild('Услуги', '/company/service')
                ->addChild('Склады', '/company/storage'))
            ->setMenu((new MenuItem)
                ->setTitle('Настройки')
                ->setIcon('pe-7s-config')
                ->addChild('Компания', '/company/setting')
                ->addChild('Услуги', '/company/setting-service')
                ->setChild((new MenuItem)
                    ->setTitle('Онлайн запись')
                    ->setHref('/company/online-registration')
                    ->setComponent('OnlineRequest')
                    ->setMeta([
                        'show' => 'true',
                    ])
                    ->addChild('Настройки', '/company/online-registration/setting')))
            ->getMenuItems();

        $this->assertCount(3, $menu);

        $this->assertEquals('RouteView', $menu[0]['component']);
        $this->assertEquals('/calendar', $menu[0]['href']);
        $this->assertEquals('pe-7s-clock', $menu[0]['icon']);
        $this->assertEquals('Расписание', $menu[0]['meta']['title']);
    }
}
