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

```bash
git clone <this_project> access-manager
cd access-manager
composer install
cp .env.example .env
# отредактируйте .env (MySQL: DB_DATABASE/DB_USERNAME/DB_PASSWORD)
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```
