# Набор методов для сборки интерфейса администрирования

[![Build Status](https://travis-ci.com/dg482/red.svg?token=ZS6AeWEGWqj2e7NdaYiG&branch=main)](https://travis-ci.com/dg482/red)
[![codecov](https://codecov.io/gh/dg482/red/branch/dev/graph/badge.svg?token=QI34D86EOX)](https://codecov.io/gh/dg482/red)

Данный пакет предназначен для определения Ресурсов, построения Таблиц, Форм и Меню навигации.

Пакет **не является** самостоятельным решением для сборки раздела администрирования. Предполагается его использование в
составе интеграций к фреимворкам [Laravel](https://github.com/fast-dog/adm) и Yii.

## Определение используемых сущностей

### Ресурсы

Ресурсы предназначены для выполнения базовых операций **CRUD** с заданной моделью. Во время инициализации ресурса
определяется адаптер БД, реализующий `\Dg482\Red\Adapters\Interfaces\AdapterInterfaces`, с помощью которого производится
получение списка полей таблицы модели и инициализация полей ресурса.

Через адаптер вызываются команды **C**reate, **R**ead, **U**pdate, **D**elete. 
Расширение командного интерфейса предусмотрено за счет выполнения действий, каждое действие может определять 
собственную команду адаптера.

###### Схема выполнения запроса к БД через команду адаптера

<p style="text-align: center">
<img src="/assets/ResourceDiagram.png" />
</p>


Ресурс должен содержать определение модели, правил проверки ввода данных, название полей с учетом локализации, 
параметры отвечающие за визуальное оформление пунктов меню.

Метод `Resource::getFormModel()` должен вернуть форму модели расширяющую базовую реализацию `Dg482\Red\Builders\Form\BaseForms`

###### Форма WebinarItemForm для ресурса WebinarResource

```php
namespace App\Resources\Webinar\Forms;

use App\Models\Webinar;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\HiddenField;

/**
 * Class WebinarItemForm
 * @package App\Resources\Webinars\Forms
 */
class WebinarItemForm extends BaseForms
{
    /**
     * Category constructor.
     * @param  Webinar  $model
     */
    public function __construct(Webinar $model)
    {
        $this->setTitle('Вебинар');// заголовок формы
        $this->setFormName('webinar/item'); // идентификатор формы
        $this->setModel($model); // определение модели в контексте формы
    }

    /**
     * Переопределение поля url
     * 
     * @param  Field  $field
     * @return Field
     */
    public function formFieldUrl(Field $field): Field
    {
        return $field->hideTable();// скрыть поле в таблице
    }

    /**
     * Переопределение типа поля id
     * @param  Field  $field
     * @return Field
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    public function formFieldId(Field $field): Field
    {
        return (new HiddenField)
            ->setField($field->getField())
            ->setValue($field->getValue()->getValue())
            ->hideTable();
    }
}

```



###### Ресурс WebinarResource

```php
namespace App\Resources\Webinar;

use App\Models\Webinar;
use App\Resources\Webinar\Forms\WebinarItemForm;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Resource\Resource;

/**
 * Class WebinarResource
 * @package App\Resources\Webinars
 */
class WebinarResource extends Resource
{
    /** @var string */
    protected string $resourceModel = Webinar::class; // Определение модели

    /**
     * @param  string  $context
     * @return Resource
     */
    public function initResource(string $context = ''): Resource
    {
        // определение параметров отображения
        $this->setIcon('video-camera') // иконка в пункте меню
            ->setTitle('Вебинары')// название пункта меню
            ->setLabels([// подписи полей формы, колонок в таблице
                'title' => 'Название',
                'url' => 'Ссылка на Вебинар',
            ])
            ->setHiddenFields([// скрытые поля
                'deleted_at',
            ])
            ->setContext(__CLASS__);
            
        // определение валидаторов
        $this->validators = [
            'title' => ['required', 'max:80'],
        ];

        return $this;
    }

    /**
     * Return form model
     *
     * @return BaseForms
     */
    public function getFormModel(): BaseForms
    {
        if (!$this->formModel) {
            $this->setForm(new WebinarItemForm(new $this->resourceModel));
        }

        return parent::getFormModel();
    }
}

```

Также ресурсы можно использовать как статичные генераторы произвольного интерфейса.