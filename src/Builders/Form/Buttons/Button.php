<?php

namespace Dg482\Red\Builders\Form\Buttons;

/**
 * Class Button
 * @package App\Admin\Builder\Form\Buttons
 */
class Button extends BaseButton
{
    /**
     * @param  string  $text
     * @return $this
     */
    public function make(string $text): Button
    {
        $this->setText($text);

        return $this;
    }
}
