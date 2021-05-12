<?php

namespace Dg482\Red\Builders\Form\Fields;

use Closure;
use Dg482\Red\Builders\Form\Fields\Values\FieldValue;
use Dg482\Red\Builders\Form\Fields\Values\FieldValues;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Dg482\Red\Exceptions\BadVariantKeyException;
use Dg482\Red\Exceptions\EmptyFieldNameException;

/**
 * Class SelectField
 * @package Dg482\Red\Builders\Form\Fields
 */
class SelectField extends StringField
{
    /** @var string */
    protected const FIELD_TYPE = 'select';

    /** @var array[FieldValue] */
    protected array $variants = [];

    /**
     * SelectField constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }

    /**
     * @param  StringValue  $value
     * @return $this
     */
    protected function pushVariant(StringValue $value): SelectField
    {
        array_push($this->variants, $value);

        return $this;
    }

    /**
     * @param  array  $variants
     * @return $this
     * @throws BadVariantKeyException
     */
    public function addVariants(array $variants): SelectField
    {
        array_map(function (array $variant) {
            if (empty($variant['id']) || empty($variant['value'])) {
                throw new BadVariantKeyException();
            }
            $this->pushVariant((new StringValue($variant['id'], $variant['value'])));
        }, $variants);

        return $this;
    }

    /**
     * @return StringValue|FieldValues
     * @throws EmptyFieldNameException|BadVariantKeyException
     */
    public function getValue()
    {
        $value = $this->request($this->getField(), $this->isMultiple() ? [] : 0);

        if (!empty($value)) {
            if (!$this->isMultiple()) {
                return $this->getVariantById($value);
            } else {
                if (!$this->value instanceof FieldValues) {
                    $this->value = new FieldValues();
                }
                array_map(function (array $value) {
                    if (empty($value['id'])) {
                        throw new BadVariantKeyException();
                    }
                    $this->value->push($this->getVariantById($value['id']));
                }, $value);
            }
        }

        return $this->value;
    }

    /**
     * @param  int  $id
     * @return StringValue
     */
    protected function getVariantById(int $id): StringValue
    {
        $result = array_filter($this->variants, function (FieldValue $val) use ($id) {
            return ($id === $val->getId());
        });

        $value = (new StringValue);

        if (count($result)) {
            $value = current($result);
        }

        return $value;
    }

    /**
     * @param  bool  $isClientValidator
     * @return array
     * @throws EmptyFieldNameException
     */
    public function getFormField(bool $isClientValidator = false): array
    {
        $result = parent::getFormField($isClientValidator);

        $result['variants'] = array_map(function (StringValue $value) {
            return [
                'id' => $value->getId(),
                'value' => $value->getValue(),
            ];
        }, $this->variants);

        return $result;
    }

    /**
     * Вывод форматированного значения на печать
     * @return string
     * @throws BadVariantKeyException
     * @throws EmptyFieldNameException
     */
    public function getPrintValue(): string
    {
        $printFn = $this->getPrintFn();

        $result = [];
        if ($this->isMultiple()) {
            $result = array_map(function (FieldValue $value) {
                return $value->getValue();
            }, $this->getValue()->getValues());
        } else {
            $id = $this->getValue()->getValue();
            if (!empty($id)) {
                $variant = $this->getVariantById($id);
                if ($variant) {
                    $result[] = $variant->getValue();
                }
            }
        }

        if ($printFn instanceof Closure) {
            return $printFn($this, $result);
        }

        return implode(', ', $result);
    }

    /**
     * @return array
     */
    public function getVariants(): array
    {
        return $this->variants;
    }
}
