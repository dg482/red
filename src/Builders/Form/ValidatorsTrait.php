<?php

namespace Dg482\Red\Builders\Form;

use Dg482\Red\Builders\Form\Fields\Field;

/**
 * Trait ValidatorsTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait ValidatorsTrait
{
    /**
     * Массив валидаторов
     * @var array
     */
    protected array $validators = [];

    /** @var array */
    protected array $error_messages = [];

    /** @var array 'blur' | 'change' | ['change', 'blur'] */
    protected array $trigger = ['blur', 'change'];

    /** @var bool */
    protected bool $required = false;

    /**
     * @return $this|Field
     */
    public function setRequired(): Field
    {
        $this->addValidators('required');

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * Валидаторы
     *
     * @return array
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * Валидаторы для UI
     *
     * @return array
     */
    public function getValidatorsClient(): array
    {
        return array_map(function (array $validator) {
            $result = [
                'type' => $validator['type'] ?? self::getType(),
                'trigger' => $validator['trigger'],
                'message' => $validator['message'],
            ];
            switch ($validator['rule']) {
                case 'required':
                    $result['required'] = true;
                    break;
                case 'email':
                    $result['type'] = 'email';
                    break;
                default:
                    array_map(function (string $idx) use (&$result, $validator) {
                        if (isset($validator[$idx])) {
                            $result[$idx] = $validator[$idx];
                        }
                    }, ['min', 'max', 'length']);
                    break;
            }
            switch ($result['type']) {
                case 'enum':
                    $result['enum'] = $validator['enum'];
                    break;
                default:
                    break;
            }

            return $result;
        }, $this->validators);
    }

    /**
     * @param  array  $validators
     * @return $this
     */
    public function setValidators(array $validators): self
    {
        $this->validators = $validators;

        return $this;
    }

    /**
     * @param  string  $rule
     * @param  string|null  $message
     * @param  string|null  $idx
     * @return $this
     */
    public function addValidators(string $rule, ?string $message = '', ?string $idx = ''): self
    {
        $resultRule = [
//            'idx' => $idx,
            'rule' => $rule,
            'trigger' => $this->trigger,
            'message' => $message ?? '',
            'type' => ($this->isMultiple()) ? 'array' : $this->getFieldValidateType()
        ];

        $this->initRule($rule, $this->getName(), $resultRule);

        array_push($this->validators, $resultRule);

        return $this;
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->error_messages;
    }

    /**
     * @param  array  $error_messages
     * @return $this
     */
    public function setErrorMessages(array $error_messages): self
    {
        $this->error_messages = $error_messages;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrigger(): string
    {
        return implode('|', $this->trigger);
    }

    /**
     * @param  string|array  $trigger
     * @return void
     */
    public function setTrigger(array $trigger): void
    {
        $this->trigger = $trigger;
    }

    /**
     * @param  string  $idx
     * @param  string  $name
     * @param  array  $rule
     */
    protected function initRule(string $idx, string $name, array &$rule)
    {
        $attribute = [
            'attribute' => '"'.$name.'"',
        ];
        $arguments = $this->getRuleArgument($idx);
        $idx = $this->getRuleIdx($idx);

        switch ($idx) {
            case 'required':
                $rule['required'] = true;
                break;
            case 'max':
            case 'size':
            case 'min':
                if (isset($arguments[1])) {
                    $rule[$idx === 'size' ? 'max' : $idx] = (int) $arguments[1];
                    $rule['length'] = (int) $arguments[1];
                }
                break;
            case 'in':
                $rule['type'] = 'enum';
                $rule['enum'] = (isset($arguments[1])) ? explode(',', $arguments[1]) : [];
                break;
            default:
                $rule['type'] = 'any';
                break;
        }
        if (empty($rule['message'])) {
            $rule['message'] = $this->trans('validation.'.$idx, $attribute);
        }
    }

    /**
     * @param  string  $idx
     * @return string
     */
    private function getRuleIdx(string $idx): string
    {
        $_rule = $this->getRuleArgument($idx);

        return (count($_rule)) ? current($_rule) : $idx;
    }

    /**
     * @param  string  $rule
     * @return array
     */
    private function getRuleArgument(string $rule): array
    {
        return explode(':', $rule);
    }
}
