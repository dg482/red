<?php

namespace Dg482\Red\Builders\Form\Fields;

use Carbon\Carbon;

/**
 * Class Datetime
 * @package Dg482\Red\Builders\Form\Fields
 */
class Datetime extends StringField
{
    /** @var string */
    const FIELD_TYPE = 'datetime';

    /**
     * Datetime constructor.
     * @param  bool  $isMultiple
     */
    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }

    /**
     * @param  null  $value
     * @param  Field|null  $dateField
     * @return string
     */
    public function updateValue($value = null): string
    {
        return Carbon::createFromTimestamp($value)->format(Carbon::DEFAULT_TO_STRING_FORMAT);
    }
}
