Luna-park
=========================================
# Prerequisites
### Web servers
- apache 2
### Database
- MySql
### PHP
- php >=7.1
### Required PHP extensions
- php7.1-curl
- php7.1-memcache
- ...
### Composer

# Installation [for Desktop Development]
### Clone repository and install dependencies
```sh
$ git clone https://bitbucket.org/{username}/lunapark-backend path/to/install
$ cd path/to/install
$ composer update
```
### Create and configure local config 
```sh
$ cp .env.example .env
```

### Generate secret key
```sh
$ php artisan jwt:secret
```
This will update your .env file with something like JWT_SECRET=foobar

### Run Project
```sh
$ php -S localhost:8888 -t public
```

Of course, more robust local development options are available via Homestead and Valet.
After this command you can copy this link and check on your browser:
```sh
http://localhost:8888/
```

# Unit Testing

Unit testing is done using [PHPUnit](https://phpunit.de/).

To execute tests, run `vendor/bin/phpunit` from the command line while in root directory.