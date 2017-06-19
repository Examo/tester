Challenger
============================

REQUIREMENTS
------------

- PHP 5.4.0.
- composer


INSTALLATION
------------

- Clone repo
```sh
git clone https://github.com/iamborisov/challenger.git
```
- Install dependencies
```sh
composer update
```
- Create database and configure db connection (see Configuration/Database)
- Run database migrations
```sh
./yii migrate
```
- Setup your web server with default Yii2 configuration
~~~
http://www.yiiframework.com/doc-2.0/guide-start-installation.html#configuring-web-servers
~~~
- ???
- PROFIT!

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```