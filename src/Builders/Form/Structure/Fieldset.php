<?php

namespace Dg482\Red\Builders\Form\Structure;

use Dg482\Red\Builders\Form\Fields\FieldTrait;

/**
 * Class Fieldset
 * @package App\Admin\Builder\Form\Structure
 */
class Fieldset extends BaseStructure
{
    use FieldTrait;

    /** @var string */
    public const FIELD_TYPE = 'fieldset';

    /**
     * @param  string  $class
     * @return $this
     */
    public function setCssClass(string $class): self
    {
        $this->attributes['fieldset']['class'] = $class;
        $this->attributes['legend']['class'] = $class;

        return $this;
    }
}
