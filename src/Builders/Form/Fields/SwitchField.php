<?php

namespace Dg482\Red\Builders\Form\Fields;

use Dg482\Red\Exceptions\BadVariantKeyException;
use Dg482\Red\Exceptions\EmptyFieldNameException;

/**
 * Class SwitchField
 * @package Dg482\Red\Builders\Form\Fields
 */
class SwitchField extends SelectField
{
    /** @var string */
    const FIELD_TYPE = 'switch';

    /**
     * @var array[]
     */
    protected array $variants = [];

    /** @var string */
    private string $action = 'switch/field';

    /**
     * SwitchField constructor.
     * @param  bool  $isMultiple
     * @throws BadVariantKeyException
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct(false);

        $this->addVariants([
            ['id' => -1, 'value' => 'Нет'],
            ['id' => 1, 'value' => 'Да'],
        ]);
    }

    /**
     * @param  string  $value
     * @return Field
     */
    public function setValue(string $value = ''): Field
    {
        if ((bool) $value) {
            $this->value->setId(1);
            $this->value->setValue('true');
        } else {
            $this->value->setId(-1);
            $this->value->setValue('');
        }

        return $this;
    }

    /**
     * @return Field
     */
    public function enable(): Field
    {
        $this->setState(true);

        return $this;
    }

    /**
     * @return Field
     */
    public function disable(): Field
    {
        $this->setState(false);

        return $this;
    }

    /**
     * @param  bool  $state
     * @return Field
     */
    public function setState(bool $state = false): Field
    {
        $this->setValue($state ? 'true' : '');

        return $this;
    }

    /**
     * Массив параметров поля для отрисовки в UI
     * @param  bool  $isClientValidator
     * @return array
     * @throws EmptyFieldNameException
     */
    public function getFormField(bool $isClientValidator = false): array
    {
        $result = parent::getFormField($isClientValidator);

        $result['value'] = !empty($result['value']);

        return $result;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param  string  $action
     * @return SwitchField
     */
    public function setAction(string $action): SwitchField
    {
        $this->action = $action;

        return $this;
    }
}
