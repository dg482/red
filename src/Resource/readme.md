### Работа с Формами ресурсов

Для работы с ресурсами как с формами необходимо определить класс формы унаследованный от `Dg482\Red\Builders\Form\BaseForms`.
В конструкторе Вашей реализации необходимо указать обязательные для формы параметры:

* Идентификатор (имя) формы
* Модель с которой будет происходить взаимодействие


При определении идентификатора (имени) формы, обязательно указание имени ресурса в первой части, например `user/identity`
задает форму `Identity` для ресурса `UserResource`.

При формировании полей ресурса возможно их переопределение, дополнение параметрами и возвращение поля другого типа.
Для этого в реализации модели формы определяются методы с комбинированным именем, содержащим в первой части префикс `formField`
и суффикс имя поля в верхнем регистре, пример имени метода для поля пароль: `formFieldPassword`.



```php

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\PasswordField;
use Dg482\Red\Builders\Form\FormModelInterface;
use Dg482\Red\Builders\Form\Structure\Fieldset;
use Exception;
 

/**
 * Class Profile
 * @package App\Models\Forms\User
 */
class Identity extends BaseForms implements FormModelInterface
{
    /**
     * Identity constructor.
     */
    public function __construct()
    {
        $this->setTitle("Данные пользователя");// Определение заголовка формы
        $this->setFormName('user/identity');// Определение уникального имени формы
        $this->setModel(new UserModel());// Определение модели для формы
    }

    /**
     * @return array
     * @throws Exception
     */
    public function resourceFields(): array
    {
        return [
            Fieldset::make("", 'general_fieldset')
                ->setItems($this->fields()),
        ];
    }

    /**
     * Password field overwrite method
     *
     * @param  Field  $field
     * @return Field
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    public function formFieldPassword(Field $field): Field
    {
        $passwordField = new PasswordField();
        $passwordField->setField($field->getField());
        $passwordField->setName($field->getName());
        $passwordField->hideTable();// hide password field in tables

        return $passwordField;
    }
}


```

Обработка значений поля перед сохранением производится за счет метода модификатора содержащего в первой части имени префикс `saveField`
и суффикс имя поля в верхнем регистре, пример имени метода для поля пароль: `saveFieldPassword`.

```php

    /**
     * @param Field $password
     * @param $value
     * @return StringValue
     */
    public function safeFieldPassword(Field $password, array $request): StringValue
    {
        return new StringValue(0, Hash::make($request[$password->getField()]));
    }

```

Для отображения (форматирования) значения поля в таблице (в форме для печати), используется метод `Field::getPrintValue()`. 

В случае необходимости переопределения вывода на печать, для каждого поля может быть определено замыкание принимающее в себя экземпляр поля и массив в исходными значениями.

```php
        $field->setPrintFn(function (Field $field, array $values) {
            return implode(', ', array_reverse($values));
        });
```