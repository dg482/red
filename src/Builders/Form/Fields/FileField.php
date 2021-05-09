<?php

namespace Dg482\Red\Builders\Form\Fields;

use Closure;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Dg482\Red\Interfaces\ResourceAssetsInterface;
use Dg482\Red\Model;

/**
 * Class File
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class FileField extends SelectField
{
    /** @var string */
    const FIELD_TYPE = 'file';

    /** @var string */
    const MIMES_IMAGE = 'jpeg,bmp,png,jpg';

    /** @var string */
    const MIMES_DOCUMENT = 'xls,xlst,doc,docx,pdf';

    /** @var string */
    protected string $uploadAction = 'api/form/upload';

    /** @var int */
    protected int $type = 0;

    /** @var null|Closure */
    protected ?Closure $store = null;

    /** @var ResourceAssetsInterface|null */
    protected ?ResourceAssetsInterface $assets = null;

    /**
     * Массив параметров поля для отрисовки в UI
     * @param  bool  $isClientValidator
     * @return array
     * @throws EmptyFieldNameException
     */
    public function getFormField(bool $isClientValidator = false): array
    {
        $result = parent::getFormField();

        $result['multiple'] = $this->isMultiple();
        $result['action'] = $this->getUploadAction();
        $result['data'] = $this->getData();

        return $result;
    }

    /**
     * @return string
     */
    public function getUploadAction(): string
    {
        return $this->uploadAction;
    }

    /**
     * @param string $uploadAction
     * @return FileField
     */
    public function setUploadAction(string $uploadAction): FileField
    {
        $this->uploadAction = $uploadAction;

        return $this;
    }


    /**
     * @param Model $model
     * @param Model $relation
     * @return Field
     * @throws EmptyFieldNameException
     */
    public function setFieldRelation(Model $model, Model $relation): Field
    {
        if (($relation instanceof Model) && isset($relation->id) && $relation->id) {
            $this
                ->setData([
                    'owner' => $model->id,
                    'type' => $this->getType(),
                    'form' => $this->request('form', ''),
                    'field' => $this->getField(),
                    'relation' => $relation,
                ])->setFieldValue('');

            $this->relation = $relation;
        }

        return $this;
    }

    /**
     * @param $value
     * @return Field
     */
    public function setFieldValue($value): Field
    {
        return $this;
    }

    /**
     * @return Closure|null
     */
    public function getStore(): ?Closure
    {
        return $this->store;
    }

    /**
     * @param Closure|null $store
     * @return FileField
     */
    public function setStore(?Closure $store): FileField
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @param  $file
     * @return mixed
     */
    public function storeFile($file)
    {
        $params = $this->getStore()($file);
        if ($this->getAssets()) {
            $this->getAssets()->store($params);
        }

        return $params;
    }

    /**
     * @return ResourceAssetsInterface|null
     */
    public function getAssets(): ?ResourceAssetsInterface
    {
        return $this->assets;
    }

    /**
     * @param  ResourceAssetsInterface|null  $assets
     * @return FileField
     */
    public function setAssets(?ResourceAssetsInterface $assets): FileField
    {
        $this->assets = $assets;

        return $this;
    }
}
