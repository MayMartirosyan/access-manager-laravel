# AccessManager (Laravel 12 + PHP 8 + Sanctum + Inertia React)

Уникальный учебный проект с REST API для пользователей/ролей, бизнес-логикой  API-кредитов, аутентификацией по токенам, админ-правами, кэшированием, оптимизацией запросов и тестами (Pest).

## Стек

-   PHP 8.x, Laravel 12.26
-   MySQL + Eloquent ORM
-   Sanctum (личные токены)
-   Inertia.js + React (SPA внутри проекта)
-   Pest для тестов
-   Vite для сборки фронтенда

## Бизнес-логика

-   Роль `Administrator` — безлимит (стратегия `UnlimitedPolicy`).
-   Администратор имеет бесконечный доступ к спецальной странице , а также доступ к спискам пользователей и ролей, может удалить юзеров или изменить их или добавить новых (реализована в laravel но не в React)
-   Обычные юзеры имеют доступ к специальной странице только ограничено раз , после истечения кредитов доступ закрывается , также не имеют доступ к страницам списка юзеров

## Установка

Обратим внимание что проект написан на Laravel 12.26, php 8.2 а дб использвано mysql 8.4 , поэтому рекомендуется загрузить их прежде чем начать запуск приложения

```bash

git clone git@github.com:MayMartirosyan/access-manager-laravel.git

```

В терминале в mysql логинимся как root пользователь

```bash

mysql -u root -p

здесь для удобства я сразу взял данные из .env.example

CREATE DATABASE access_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'access_user'@'localhost' IDENTIFIED BY 'admin_password';

GRANT ALL PRIVILEGES ON access_manager.* TO 'access_user'@'localhost';

FLUSH PRIVILEGES;

```

После этого заходим в папку проекта

```bash
cd access-manager
```

Загружаем пакеты (как для laravel так и для inertia.js + React - а со сборкой vite )

```bash

composer install
npm install

```

Создаем .env

```bash

cp .env.example .env

```

генерируем ключ приложения

```bash

php artisan key:generate

```

Делаем миграции и сиды

```bash

php artisan migrate --seed

```

Запускаем и фронтенд и бекенд

```bash

npm run dev
php artisan serve

```

Проект успешно запущено !

## Тесты

Для тестов нам потребуется сначала запустить кастомную команду которая подготовит среду дб для тестирования

Но перед этим создадим в mysql дб и юзер для тестовой среды

```bash

mysql -u root -p

CREATE DATABASE access_manager_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'access_user_test'@'localhost' IDENTIFIED BY 'secure_password';

GRANT ALL PRIVILEGES ON access_manager_test.* TO 'access_user_test'@'localhost';

FLUSH PRIVILEGES;

```

После этого выполняем в корне проекта команду

```bash

php artisan test:prepare --fresh --seed --env=testing

```

После этого уже комманду для теста

```bash

php artisan test

```

Запуск тестов с измерением покрытия (должно быть установлено Xdebug или PCOV,
см. https://xdebug.org/docs/install, https://github.com/krakjoe/pcov
)

```bash

php artisan test --coverage

```

> © В проекте могут быть баги, но сделано с любовью и энтузиазмом от Маис Мартиросян.
> Дальнейшая поддержка и добавление новых фич проекта будет легким ©
