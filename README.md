# AccessManager (Laravel 12 + PHP 8 + Sanctum + Inertia React)

Уникальный учебный проект с REST API для пользователей/ролей, бизнес-логикой дневных API-кредитов, аутентификацией по токенам, админ-правами, кэшированием, оптимизацией запросов и тестами (Pest).

## Стек

-   PHP 8.x, Laravel 12
-   MySQL + Eloquent ORM
-   Sanctum (личные токены)
-   Inertia.js + React (SPA внутри того же проекта)
-   Pest для тестов
-   Vite для сборки фронтенда

## Бизнес-логика (уникальная)

-   Каждая роль имеет `daily_credits` — суточный лимит API-вызовов.
-   Middleware `credits` списывает кредит на вызов user-эндпоинтов.
-   Роль `Administrator` — безлимит (стратегия `UnlimitedPolicy`).
-   Суточный лимит сбрасывается при смене даты на первом запросе.

## Установка

Обратим внимание что проект написан на Laravel 12, php 8.2 а дб использвано mysql 8.4 , поэтому рекомендуется загрузить их прежде чем начать запуск приложения

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
