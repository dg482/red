<?php

namespace Dg482\Red\Builders\Form\Structure;

use Dg482\Red\Builders\Form\Fields\FieldTrait;

/**
 * Class TabPane
 * @package App\Admin\Builder\Form\Structure
 */
class TabPane extends BaseStructure
{
    use FieldTrait;

    /** @var string */
    public const FIELD_TYPE = 'tab-pane';

    public function __construct()
    {

    }
}
