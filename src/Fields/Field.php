<?php

namespace Dg482\Red\Fields;

use Dg482\Red\TranslateTrait;

/**
 * Class Field
 * @package Dg482\Red\Fields
 */
abstract class Field
{
    use ValidatorsTrait, AttributeTrait, TranslateTrait;

    protected const FIELD_TYPE = '';

    /** @var int $id */
    public int $id = 0;

    /**
     * Field label
     * @var string $name
     */
    protected string $name = '';

    /**
     * Model field
     * @var string $field
     */
    protected string $field = '';

    /**
     * Поле выключено?
     * @var bool $disabled
     */
    protected bool $disabled = false;

    /** @var mixed */
    protected $value;

    /** @var array */
    protected array $data = [];

    /**
     * Массив параметров поля для отрисовки в UI
     * @return array
     */
    public function getFormField(): array
    {
        return [
            'id' => empty($this->id) ? time() + rand(1, 99999) : $this->id,
            'name' => $this->getName(),
            'type' => $this->getFieldType(),
            'field' => $this->getField(),
            'disabled' => $this->isDisabled(),
            'attributes' => $this->getAttributes(),
            'validators' => $this->getValidators(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function setName(string $name): Field
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this::FIELD_TYPE;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param  string  $field
     * @return $this
     */
    public function setField(string $field): Field
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param  bool  $disabled
     * @return $this|self
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @param  array  $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): Field
    {
        if (false === isset($attributes['showTable'])) {
            $attributes['showTable'] = true;
        }

        if (false === isset($attributes['showForm'])) {
            $attributes['showForm'] = true;
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Скрыть поле в форме
     * @return $this
     */
    public function hideForm(): Field
    {
        $this->attributes['showForm'] = false;

        return $this;
    }

    /**
     * Скрыть поле в таблице
     * @return $this
     */
    public function hideTable(): Field
    {
        $this->attributes['showTable'] = false;

        return $this;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param  array  $data
     * @return Field
     */
    public function setData(array $data): Field
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param  string  $rule
     * @param  string|null  $message
     * @param  string|null  $idx
     * @return $this
     */
    public function addValidators(string $rule, ?string $message = '', ?string $idx = ''): Field
    {
        $resultRule = [
            'idx' => $idx,
            'rule' => $rule,
            'trigger' => $this->trigger,
            'message' => $message ?? '',
            'type' => ($this->isMultiple()) ? 'array' : $this->getFieldType(),
        ];

        $this->initRule($rule, $this->getName(), $resultRule);

        array_push($this->validators, $resultRule);

        return $this;
    }

    /**
     * @param  string  $name
     * @return mixed|null
     */
    protected function request(string $name)
    {
        return $_REQUEST[$name] ?? null;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
