# Персональный брокер — интеграция лидов

Мини-сайт для отправки лидов и просмотра их статусов через Belmar CRM API.

**Демо:** https://framino.pro

## Страницы

| URL | Описание |
|-----|----------|
| `/` | Форма заявки |
| `/?page=thank-you&id=123` | Страница «Спасибо» |
| `/?page=statuses` | Статусы лидов с фильтром по дате |

## Структура проекта

```
config.php          — настройки API (токен, box_id, offer_id и т.д.)
functions.php       — вся логика: API, валидация, рендер
public/index.php    — роутинг страниц
templates/          — шаблоны HTML
public/assets/      — CSS и JS
```

## Требования

- PHP 8.1+
- PHP cURL

## Запуск локально

```bash
cd public
php -S localhost:8080 router.php
```

## Деплой

Document root веб-сервера — папка `public/`.

Папка `storage/` должна быть доступна для записи PHP (обычно `www-data`):

```bash
mkdir -p storage
chown www-data:www-data storage
chmod 775 storage
```

Composer не обязателен — проект работает на чистом PHP без зависимостей.

## API

**URL:** `https://crm.belmar.pro/api/v1`

| Метод | Endpoint | Назначение |
|-------|----------|------------|
| POST | `/addlead` | Отправка лида |
| POST | `/getstatuses` | Получение статусов |

Статичные параметры: `box_id=28`, `offer_id=5`, `countryCode=GB`, `language=en`, `password=qwerty12`.

Динамические: `ip` (реальный IP клиента), `landingUrl` (домен сайта).

## Репозиторий

https://github.com/sharkleadstech1-dot/TechnicalSpecifications

```bash
git clone https://github.com/sharkleadstech1-dot/TechnicalSpecifications.git
cd TechnicalSpecifications
```
