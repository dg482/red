<?php

namespace Dg482\Red\Interfaces;

/**
 * Interface FormModelInterface
 * @package App\Admin\Builder\Form
 */
interface FormModelInterface
{
    /**
     * @return array
     */
    public function resourceFields(): array;

    /**
     * @param  array  $structure
     */
    public function setFormStructure(array $structure): void;

    /**
     * @param  array  $fields
     * @return array
     */
    public function sortFields(array $fields): array;
}
