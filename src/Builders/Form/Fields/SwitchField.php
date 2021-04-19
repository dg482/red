<?php

namespace Dg482\Red\Builders\Form\Fields;

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

    /** @var string  */
    private string $action = 'switch/field';

    /**
     * Switcher constructor.
     * @param  bool  $isMultiple
     * @throws \Dg482\Red\Exceptions\BadVariantKeyException
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);

        $this->addVariants([
            ['id' => -1, 'value' => 'Нет'],
            ['id' => 1, 'value' => 'Да'],
        ]);
    }
}
