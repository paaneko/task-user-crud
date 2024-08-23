## Початок Роботи

Перед початком переконайтеся, що у вас встановлені Docker та Docker Compose. Для використання Makefile необхідно встановити GNU Make.

### Ініціалізація Проекту

Для побудови, встановлення залежностей та запуску проекту виконайте:

```sh
make init
```

# Налаштування

## Режими роботи
Визначте режим роботи додатка через файл .env:

Режим розробки (dev): Надає детальний звіт про помилки.
`APP_ENV=dev  # для режиму розробки`

Режим виробництва (prod): Обмежує відображення інформації про помилки для безпеки.
`APP_ENV=prod # для режиму виробництва`

**ОБОВЯЗКОВО ПЕРЕМКНІТЬ В РЕЖИМ APP_ENV=prod, ЯКЩО ХОЧЕТЕ ОТРИМАТИ КРАСИВИЙ ERROR HANDLER БЕЗ ТРЕЙСУ**

# Bearer token

Видача Bearer токену "testAdmin" або "testUser" можлива для будь-якого користувача. Щоб отримати токен, необхідно виконати спеціальну консольну команду та вказати логін користувача, для якого буде видано токен обраного типу за допомогою `--testAdmin` та `--testUser`.

Наприклад:
```sh
docker compose exec php bin/console app:get-bearer-token `бажаний логін` `--testAdmin aбо --testUser`
```

Для того, щоб отримати Bearer токен з типом "testAdmin", виконайте команду:
```sh
docker compose exec php bin/console app:get-bearer-token admin --testAdmin
```

Для того, щоб отримати Bearer токен з типом "testUser", виконайте команду:
```sh
docker compose exec php bin/console app:get-bearer-token user --testUser
```

Пре-завантажений юзер в бд, його можна використовувати для тесту різних токенів:
```json
{
  "id": "1",
  "login": "test",
  "phone": "123123",
  "pass": "testPassword"
}

```

### Запуск Проекту
```sh
make up
```

### Зупинка Проекту
```sh
make down
```

## Тести

### Запуск Unit-тестів

```sh
make test-unit
```

## Статичний аналіз коду

### Запуск Psalm

```sh
make psalm-check
```