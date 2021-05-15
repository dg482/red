<?php

namespace Dg482\Red\Builders\Form;

use Closure;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\SelectField;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Exception;

/**
 * Trait FilterTrait
 * @package Dg482\Red\Builders\Form
 */
trait FilterTrait
{
    /** @var array $filter */
    protected array $filter = [];

    /** @var bool  */
    protected bool $filterMultiple = false;

    /** @var Closure|null */
    protected ?Closure $filterFn = null;

    /** @var array $scopedSlots */
    protected array $scopedSlots = [];

    /**
     * @return array
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * @param  array  $filter
     * @return Field
     */
    public function setFilter(array $filter): Field
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return array
     */
    public function getScopedSlots(): array
    {
        return $this->scopedSlots;
    }

    /**
     * @param  array  $scopedSlots
     * @return Field
     */
    protected function setScopedSlots(array $scopedSlots): Field
    {
        $this->scopedSlots = $scopedSlots;

        return $this;
    }

    /**
     * @return Field
     */
    public function setFilterText(): Field
    {
        $this->setScopedSlots(['filterDropdown' => 'filterDropdown', 'filterIcon' => 'filterIcon']);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setFilterVariant(): Field
    {
        if (!$this instanceof SelectField) {
            throw new Exception('Field not supported search variant');
        }
        $filters = [];
        array_map(function (StringValue $value) use (&$filters) {
            $filters[] = [
                'text' => $value->getValue(),
                'value' => $value->getId(),
            ];
        }, $this->getVariants());
        if (!empty($filters)) {
            $this->setFilter($filters);
        }

        $this->setScopedSlots(['filterIcon' => 'filterIcon']);

        return $this;
    }

    /**
     * @return Closure|null
     */
    public function getFilterFn(): ?Closure
    {
        return $this->filterFn;
    }

    /**
     * @param  Closure|null  $filterFn
     * @return Field
     */
    public function setFilterFn(?Closure $filterFn): Field
    {
        $this->filterFn = $filterFn;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterMultiple(): bool
    {
        return $this->filterMultiple;
    }

    /**
     * @param  bool  $filterMultiple
     * @return Field
     */
    public function setFilterMultiple(bool $filterMultiple = true): Field
    {
        $this->filterMultiple = $filterMultiple;

        return $this;
    }
}
