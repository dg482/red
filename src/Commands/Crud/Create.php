<?php

namespace Dg482\Red\Commands\Crud;

/**
 * Class Create
 * @package Dg482\Red\Commands\Crud
 */
class Create extends Update
{

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $model = $this->getModel();
        $request = $this->getData();
        $insert = [];
        array_map(function (string $field) use (&$insert, $request) {
            if (isset($request[$field])) {
                $insert[$field] = $request[$field];
            }
        }, $model->getFillable());

        if (false === empty($insert)) {
            $model = $model->create($insert);

            return $model->id > 0;
        }

        return false;
    }
}
