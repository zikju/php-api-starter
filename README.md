# php-api-starter
A starter source code for a handy PHP restful API development.
Created for OOP practice and personal use.

# Requirements
* PHP: >=7.4 
* MySQL: >=5.7
* Composer

# Packages used
[ThingEngineer/PHP-MySQLi-Database-Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class "PHP-MySQLi-Database-Class") <br />
_MysqliDb is MySQLi wrapper and object mapper with prepared statements._

[steampixel/simplePHPRouter](https://github.com/steampixel/simplePHPRouter "simplePHPRouter") <br />
_Simple and small single class PHP router that can handle the whole URL routing._

[Respect/Validation](https://github.com/Respect/Validation "Validation") <br />
_"The most awesome validation engine ever created for PHP!"_

[firebase/php-jwt](https://github.com/firebase/php-jwt "php-jwt") <br />
_A simple library to encode and decode JSON Web Tokens (JWT) in PHP._

[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv "phpdotenv") <br />
_Loads environment variables from `.env` to `getenv()`, `$_ENV` and `$_SERVER` automagically._

# Features
* JWT Token Authentication
* JWT Refresh Tokens
* Users management
* More to come...

# Installation
* Clone repository:<br />
`git clone https://github.com/zikju/php-api-starter`

* Install composer dependencies:<br />
`composer install`

* Import file `database.sql` into your MySQL database

* Edit file **_.env.example_**.<br />
Change MySQL logins variables to match your own database settings:

```
 DB_HOST = localhost
 DB_PORT = 3306
 DB_DATABASE = database_name
 DB_USERNAME = root
 DB_PASSWORD =
```

* Rename file **_.env.example_**  to **_.env_**



* (optional) For easiest way to test endpoints - import file `POSTMAN_ENDPOINTS.json` into your [Postman](https://www.postman.com/ "Postman") workflow. <br />
After file import - find Collection variables and change `API_URL` to your project url.


# Endpoints

### Authentication endpoints:

Method | Endpoint | Parameters | Description
--- | --- | --- | ---
`POST` | `/auth/login` | `email` *string* **required**<br>`password` *string* **required** | login user
`GET` | `/auth/logout` |  | logout user
`GET` | `/auth/refresh-token` |  | refresh token


### Users endpoints:

_All RESTful API endpoints below requires a `Authorization: Bearer xxxx` header set on the HTTP request.<br />
**xxxx** is replaced with token generated from the `/auth/login` endpoint above._

Method | Endpoint | Parameters | Description
--- | --- | --- | ---
`POST` | `/users` | `email` *string* - **required**<br>`password` *string* - **required**<br />`role` *string*<br />`status` *string*<br />`notes` *string*<br /> | Create new user
`GET` | `/users/:id` | `:id` *integer* - **required** | Get user information
`DELETE` | `/users/:id` | `:id` *integer* - **required** | Delete user
`PUT` | `/users/:id/edit` | `:id` *integer* - **required**<br />`role` *string*<br />`status` *string*<br />`notes` *string*<br /> | Edit user common data
`PUT` | `/users/:id/edit/email` | `:id` *integer* - **required**<br />`email` *string* - **required** | Change user email

