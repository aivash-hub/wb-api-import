# WB API Import (Laravel)

Тестовое задание: импорт данных из внешнего API Wildberries.
Источник данных: тестовое API (WB API test service).

## Стек

- PHP 8.1
- Laravel 8
- Docker / docker-compose
- Laravel Octane

## Импортируемые сущности

- Orders (заказы)
- Sales (продажи)
- Stocks (остатки)
- Incomes (доходы)

## Архитектура

Импорт реализован через сервисный слой.

```
app/
 ├ Services
 │   ├ WbApiService
 │   ├ ImportOrdersService
 │   ├ ImportSalesService
 │   ├ ImportStocksService
 │   └ ImportIncomesService
 │
 ├ DTO
 │   └ OrderDto
 │
 ├ Mappers
 │   └ OrderMapper
 │
 └ Console
     └ Commands
```

### Основные компоненты

**WbApiService**

Отвечает за HTTP-запросы к API.

**Import*Service**

Содержит логику:

- пагинации
- обработки данных
- сохранения через `upsert`

**Console Commands**

Позволяют запускать импорт через CLI.

---

## Установка

1. Клонировать репозиторий
```
git clone 
cd wb-api-import
```
2. Установить зависимости
```
composer install
```
3. Создать файл окружения
```
cp .env.example .env
```
4. Настроить подключение к базе данных в `.env`

5. Выполнить миграции
```
php artisan migrate
```

---

## Запуск импорта

### Orders

```
php artisan wb:import-orders
```

### Sales

```
php artisan wb:import-sales
```

### Stocks

```
php artisan wb:import-stocks
```

### Incomes

```
php artisan wb:import-incomes
```

---

## Особенности реализации

- используется пагинация API
- обработка ошибок HTTP
- bulk insert через `upsert`
- базовая архитектурная декомпозиция (Service / DTO / Mapper)

---

## База данных

- Проект использует **MySQL**.
- Название базы данных: `wb_api_import`.
- Таблицы создаются через Laravel migrations.
- Настройки подключения задаются в `.env`:
```
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=wb_api_import
```

- Для создания таблиц необходимо выполнить миграции:
```
php artisan migrate
```

---

## Автор

Anton Ivashkin
