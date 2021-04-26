<?php

namespace Dg482\Red\Builders\Form\Fields;

use Dg482\Red\Builders\Form\AttributeTrait;
use Dg482\Red\Builders\Form\Fields\Values\FieldValues;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Dg482\Red\Builders\Form\ValidatorsTrait;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Dg482\Red\TranslateTrait;

/**
 * Class Field
 * @package Dg482\Red\Builders\Form\Fields
 */
abstract class Field
{
    use ValidatorsTrait, AttributeTrait, TranslateTrait;

    /** @var string */
    protected const FIELD_TYPE = '';

    /** @var string */
    protected const FIELD_VALIDATE_TYPE = 'any';

    /** @var int $id */
    public int $id = 0;

    /** @var string $name Field label */
    protected string $name = '';

    /** @var string $field Model field */
    protected string $field = '';

    /**
     * Поле выключено?
     * @var bool $disabled
     */
    protected bool $disabled = false;

    /** @var bool */
    protected bool $multiple = false;

    /** @var mixed */
    protected $value;

    /** @var array */
    protected array $data = [];

    /**
     * Массив параметров поля для отрисовки в UI
     * @param  bool  $isClientValidator
     * @return array
     * @throws EmptyFieldNameException
     */
    public function getFormField(bool $isClientValidator = false): array
    {
        /** @var FieldValues|StringValue|array $value */
        $value = $this->getValue();

        return [
            'id' => empty($this->id) ? time() + rand(1, 99999) : $this->id,
            'name' => $this->getName(),
            'type' => $this->getFieldType(),
            'field' => $this->getField(),
            'disabled' => $this->isDisabled(),
            'attributes' => $this->getAttributes(),
            'validators' => $isClientValidator ? $this->getValidatorsClient() : $this->getValidators(),
            'value' => (!$this->isMultiple()) ? [
                'id' => $value->getId(),
                'value' => $value->getValue(),
            ] : array_map(function (FieldValues $value) {
                return [
                    'id' => $value->getId(),
                    'value' => $value->getValue(),
                ];
            }, $value),
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
     * @throws EmptyFieldNameException
     */
    public function getField(): string
    {
        if (empty($this->field)) {
            throw new EmptyFieldNameException();
        }

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
     * @param  string  $name
     * @param  mixed  $default
     * @return mixed
     */
    protected function request(string $name, $default)
    {
        return $_REQUEST[$name] ?? $default;
    }

    /**
     * @return mixed
     * @throws EmptyFieldNameException
     */
    public function getValue()
    {
        $value = $this->request($this->getField(), $this->isMultiple() ? [] : '');
        if (!empty($value)) {
            if ($this->isMultiple()) {
                if (!$this->value instanceof FieldValues) {
                    $this->value = new FieldValues();
                }
                array_map(function ($value) {
                    $this->value->push(new StringValue((int) $value['id'], (string) $value['value']));
                }, (array) $value);
            } else {
                if (is_array($value) && isset($value['id'])) {
                    $this->value->setId((int) $value['id'])
                        ->setValue((string) $value['value']);
                } else {
                    if (is_string($value)) {
                        $this->value->setValue((string) $value);
                    }
                }
            }
        }

        return $this->value;
    }

    /**
     * @param  bool  $multiple
     * @return Field
     */
    public function setMultiple(bool $multiple): Field
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return static::FIELD_TYPE;
    }

    /**
     * @return bool
     */
    public function isShowForm(): bool
    {
        return (bool) (!isset($this->attributes['showForm'])) ? true : $this->attributes['showForm'] ?? false;
    }

    /**
     * @return bool
     */
    public function isShowTable(): bool
    {
        return (bool) (!isset($this->attributes['showTable'])) ? true : $this->attributes['showTable'] ?? false;
    }

    /**
     * @param  array  $validators
     * @return $this
     */
    public function setValidators(array $validators): self
    {
        if (empty($validators)) {
            $this->validators = [];
        } else {
            array_map(function (string $rule) {
                $idx = current(explode(':', $rule));
                if (isset($this->error_messages[$idx])) {
                    $this->addValidators($rule, $this->error_messages[$idx], $idx);
                } else {
                    $this->addValidators($rule, null, $idx);
                }
            }, $validators);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldValidateType(): string
    {
        return static::FIELD_VALIDATE_TYPE;
    }
}
