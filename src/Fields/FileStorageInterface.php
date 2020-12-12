<?php

namespace Dg482\Red\Fields;

use Dg482\Red\Builders\Form\FormModelInterface;

/**
 * Interface FileStorageInterface
 * @package Dg482\Red\Fields
 */
interface FileStorageInterface extends FormModelInterface
{
    /**
     * @return StringField
     */
    public function getPath(): StringField;
}
