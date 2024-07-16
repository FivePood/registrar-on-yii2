## Установка

### 1. PHP
* Протестировано на php-8.2.
### 2. MySQL
* База данных MySQL 5.7
* Создать базу данных и указать имя базы данных в файле:
```bash
common/config/main-local.php
```
### 3. Запуск проекта
```bash
php init
composer install
php yii migrate
```