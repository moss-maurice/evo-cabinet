Private User Cabinet Core for ModX Evolution 1.4.x
=====================
Базовый фронт личного кабинета пользователей для сайтов под управлением Modx Evo.

### Установка:
Клонирование проекта:
```sh
composer create-project mmaurice/evo-cabinet ./cabinet
```

Перейти в каталог проекта:
```sh
cd ./cabinet
```

Обновить зависимости composer (на всякий случай):
```sh
composer update
```

Установить миграции для установки базового окружения и ENV-файла:
```sh
php cli.php migrations
```

После установки миграций, появляется файл .env с рекомендованной конфигурацией. Если вы хотите самостоятельно сконфигурировать приложение, можно воспользоваться файлом .env.example.

### Использование:
Для подключения формы оплаты к туру, достаточно добавить в код страницы (шаблон или описание) следующий виртуальный чанк:
```php
{{cabinetOrder ? &price=`12000` &tourId=`154`}}
```
Параметры запроса:
-- price - обязательный параметр, указывается стоимость тура
-- tourId - не обязательный параметр, указывается id бронируемого тура.
