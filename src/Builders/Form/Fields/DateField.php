<?php

namespace Dg482\Red\Builders\Form\Fields;

use Carbon\Carbon;

/**
 * Class Date
 * @package App\Admin\Builder\Form\Fields
 */
class DateField extends DatetimeField
{
    /** @var string */
    const FIELD_TYPE = 'date';

    public function __construct(bool $isMultiple = false)
    {
        parent::__construct($isMultiple);
    }

    /**
     * @param  null  $value
     * @param  Field|null  $dateField
     * @return string
     */
    public function updateValue($value = null, ?Field $dateField = null): string
    {
        if (empty($value)) {
            return '';
        }

        if ($timestamp = strtotime($value)) {
            $value = $timestamp;
        }

        return Carbon::createFromTimestamp($value)->format('Y-m-d');
    }
}
