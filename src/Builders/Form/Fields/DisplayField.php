<?php

namespace Dg482\Red\Builders\Form\Fields;

/**
 * Class DisplayField
 * @package Dg482\Red\Builders\Form\Fields
 */
class DisplayField extends StringField
{
    /** @var string */
    const FIELD_TYPE = 'display';

    /** @var array  */
    protected array $cssStyle = [];

    /**
     * @return array
     */
    public function getCssStyle(): array
    {
        return $this->cssStyle;
    }

    /**
     * @param  array  $cssStyle
     * @return DisplayField
     */
    public function setCssStyle(array $cssStyle): DisplayField
    {
        $this->cssStyle = $cssStyle;

        return $this;
    }
}
