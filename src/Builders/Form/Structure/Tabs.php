<?php

namespace Dg482\Red\Builders\Form\Structure;

use Dg482\Red\Builders\Form\Fields\FieldTrait;

/**
 * Class Tabs
 * @package App\Admin\Builder\Form\Structure
 */
class Tabs extends BaseStructure
{
    use FieldTrait;

    /** @var string */
    public const FIELD_TYPE = 'tabs';

    /**
     * @param  string  $name
     * @return TabPane
     */
    public function pushTab(string $name): TabPane
    {
        $tabPane = (new TabPane)
            ->setName($name)
            ->setField(md5($name));

        $this->pushItem($tabPane);

        return $tabPane;
    }
}
