<?php

namespace Dg482\Red\Tests\Feature;

use Dg482\Red\Resource\Actions\ResourceAction;

/**
 * Class TestTableAction
 * @package Dg482\Red\Tests\Feature
 */
class TestTableAction extends ResourceAction
{
    public function __construct()
    {
        $this->setAction('test');
        $this->setActionUrl('/example/action/url');
        $this->setIcon('test-icon');
        $this->setText('Test Action');

        $this->setConfirm(true);
        $this->setConfirmTitle('Confirm action?');
        $this->setConfirmIcon('confirm-icon');
        $this->setConfirmOkText('Confirm test action?');
        $this->setConfirmOkType('danger');
        $this->setConfirmCancelText('Confirm cancel test action?');
        $this->setConfirmMessage('Confirm message...');
    }
}
