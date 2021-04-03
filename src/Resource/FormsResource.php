<?php

namespace Dg482\Red\Resource;

use Dg482\Red\Adapters\Adapter;

/**
 * Class FormsResource
 * @package Dg482\Red\Resource
 */
class FormsResource extends Resource
{
    /**
     * FormsResource constructor.
     * @param  Adapter  $adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);

        $this->initResource(__CLASS__);
    }
}
