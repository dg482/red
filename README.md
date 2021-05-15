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

<p style="text-align: center" align="center">
<img src="/assets/ResourceDiagram.png" />
</p>


Ресурс должен содержать определение модели, правила проверки ввода данных, название полей с учетом локализации, 
параметры, отвечающие за визуальное оформление пунктов меню.

В методе `Resource::initResource()` определяются основные параметры:
* `Resource::setTitle()` - заголовок в пункте меню, таблице элементов ресурса
* `Resource::setTitle()` - иконка в пункте меню
* `Resource::setLabels()` - подписи к полям в таблице и форме
* `Resource::setHiddenFields()` - определение скрытых полей (**Внимание!** поля будут пропущены при сборке таблицы или формы, 
  что бы присвоить полю тип `hidden` необходимо переопределить поле в форме ресурса)
* `Resource::setAssets()` - метод определяет хранилище файлов, реализацию интерфейса `ResourceAssetsInterface` для работы с медиа материалами
  (изображениями, файлами документации и тд) прикрепляемыми к модели ресурса. 

Метод `Resource::getFormModel()` должен вернуть форму модели, расширяющую базовую реализацию `Dg482\Red\Builders\Form\BaseForms`

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
    /** 
     * Определение модели
     * @var string 
     */
    protected string $resourceModel = Webinar::class;

    /**
     * Метод инициализации параметров ресурса
     * 
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
            
        // определение правил проверки ввода с учетом интеграции с фреимворком, в данном случае правила для Laravel
        $this->validators = [
            'title' => ['required', 'max:80'],
        ];

        $this->setAssets(new WebinarStorage);
        
        return $this;
    }

    /**
     * Метод возвращает форму ресурса в которой определены вспомогательные методы для работы с полями
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

<details>
  <summary><strong>Хранилище WebinarStorage для ресурса WebinarResource (Laravel)</strong></summary>

```php
namespace App\Resources\Webinar\Assets;

use App\Models\Files\Storage;
use Dg482\Red\Interfaces\ResourceAssetsInterface;

/**
 * Class WebinarStorage
 * 
 * В контексте данного примера класс WebinarStorage расширяет Storage
 * который является типовой реализацией работы с моделью БД для регистрации файлов. 
 * От проекта к проекту данная реализация может отличаться поэтому 
 * в качестве примера рассматриваются только методы интерфейса ResourceAssetsInterface.
 * 
 * @package App\Resources\Webinar\Assets
 */
class WebinarStorage extends Storage implements ResourceAssetsInterface
{
    /**
     * Удаление файла
     *
     * @return bool
     */
    public function remove(): bool
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($this->{self::PATH});

        return $this->delete();
    }

    /**
     * Получение модели по идентификатору привязки к файлу
     * @param  int  $id
     * @return ResourceAssetsInterface
     */
    public function get(int $id): ResourceAssetsInterface
    {
        $item = $this->where([
            Storage::OWNER_TYPE => Storage::TYPE_CATALOG,
            'id' => $id,
        ])->first();

        return $item ?? new self();
    }

    /**
     * Сохранение привязки загруженного файла
     *
     * @param  array  $parameter
     * @return bool
     */
    public function store(array $parameter): bool
    {
        $item = self::create([
            Storage::OWNER_TYPE => Storage::TYPE_CATALOG,
            Storage::OWNER_ID => $parameter[Storage::OWNER_ID],
            Storage::PATH => $parameter[Storage::PATH],
            Storage::STORAGE => Storage::STORAGE_LOCAL,
            Storage::FILE => $parameter[Storage::FILE] ?? $parameter[Storage::PATH],
        ]);

        return $item->id > 0 ?? false;
    }
}

```
</details>

<details>
  <summary><strong>Форма WebinarItemForm для ресурса WebinarResource (Laravel)</strong></summary>

```php
namespace App\Resources\Webinar\Forms;

use App\Models\Webinar;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\HiddenField;
use Dg482\Red\Builders\Form\Fields\DateField;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * Определение формы для работы с моделью Webinar, содержит методы модификации полей по умолчанию
 *
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
     * Переопределение поля Дата создания
     * 
     * При переопределение происходит корректировка отображения поля в форме 
     * и определение замыкания вызываемого при построение запроса из таблицы ресурса определяющего
     * условие фильтрации по полю.
     * 
     * @param  Field  $field
     * @return Field
     * @throws EmptyFieldNameException
     */
    public function formFieldCreatedAt(Field $field): Field
    {
        return (new DateField)
            ->setFilterFn(function (Builder &$builder, array $request) use ($field) {// замыкание для условия запроса к БД
                if (!empty($request['created_at'])) {
                    if (count($request['created_at']) === 1) {// если передано одно значение
                        $date = Carbon::createFromFormat('Y-m-d', current($request['created_at']));
                        // фильтруем в диапазоне начала и конца даты
                        $builder->whereBetween('created_at', [
                            $date->startOfDay()->format(Carbon::DEFAULT_TO_STRING_FORMAT),
                            $date->endOfDay()->format(Carbon::DEFAULT_TO_STRING_FORMAT),
                        ]);
                    } elseif (!empty($request['created_at'][0]) && !empty($request['created_at'][1])) {
                        $start = Carbon::createFromFormat('Y-m-d', $request['created_at'][0]);
                        $end = Carbon::createFromFormat('Y-m-d', $request['created_at'][1]);
                        // фильтруем в диапазоне начала и конца дат
                        $builder->whereBetween('created_at', [
                            $start->startOfDay()->format(Carbon::DEFAULT_TO_STRING_FORMAT),
                            $end->endOfDay()->format(Carbon::DEFAULT_TO_STRING_FORMAT),
                        ]);
                    }
                }
            })
            ->setFilterMultiple()// возможность выбора диапазона значений
            ->hideForm() // скрываем отображение в форме
            ->setField($field->getField()) // определение поля
            ->setName($field->getName())// определение названия
            ->setValue($field->getValue()->getValue());
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
</details>





Также ресурсы можно использовать как статичные генераторы произвольного интерфейса.