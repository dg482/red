<?php

namespace Dg482\Red\Fields;

use Dg482\Red\Exceptions\BadVariantKeyException;
use Dg482\Red\Values\FieldValue;
use Dg482\Red\Values\FieldValues;
use Dg482\Red\Values\StringValue;

/**
 * Class SelectField
 * @package Dg482\Red\Fields
 */
class SelectField extends StringField
{
    const FIELD_TYPE = 'select';

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
     */
    public function getValue()
    {
        $value = $this->request($this->getField(), $this->isMultiple() ? [] : 0);

        if (!$this->isMultiple()) {
            return $this->getVariantById($value);
        }

        return new StringValue();
    }

    /**
     * @param  int  $id
     * @return mixed|null
     */
    protected function getVariantById(int $id)
    {
        $result = array_filter($this->variants, function (FieldValue $val) use ($id) {
            return ($id === $val->getId());
        });

        return (count($result)) ? current($result) : null;
    }
}
