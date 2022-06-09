

To run this tool localy, you will need:

### Mongodb server

```
sudo apt-get install mongodb
```

```
sudo service mongodb status
```

### PHP

The extension mongodb-1.4.4 (see below) is compatible with PHP 7.2 or older (thus NOT with PHP 7.3).


### PECL

```
sudo apt-get install php7.2-dev php-pear
```

### Mongodb PHP extension

* https://docs.mongodb.com/ecosystem/drivers/php/
* https://pecl.php.net/package/mongodb

You will need mongodb-1.4.4:

```
sudo pecl install mongodb-1.4.4
```

To check if the correct version of mongodb extension is installed:

```
sudo pecl list
```

You will need php7.2-mbstring:

```
sudo apt-get install php7.2-mbstring
```

Edit php.ini (PHP configuration file) :
```
vim /etc/php/7.2/cli/php.ini 
```
In the dynamic extensions section, insert the following line :
```
extension=mysqli.so;
```

And to check that the extension is actually enabled and used by php:

```
php -i | grep mongo
```

### Installation

In the mon directory :
```
composer install
touch storage/app/db.sqlite
cp env.dev .env
php artisan migrate
php artisan key:generate
```

To check your installation is correct, you can run the phpunit tests:

```
sudo apt-get install php-dom
./vendor/bin/phpunit
```


### Frontend

```
npm install
npm run watch
```


