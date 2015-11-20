# Coagmento #

This is the next version of Coagmento, which is under active development.

## Developer Installation ##

### OSX and Linux ###
Coagmento is built on top of the PHP framework [Laravel](http://laravel.com/). Before setting up Coagmento, make sure your environment meets the server requirements of Laravel. Most notably, make sure your version of PHP is >= 5.5.9. On Ubuntu 14.04, the default PHP from apt-get satisfies this criteria.

#### Install Composer ####
Composer, the PHP package manager, is used to automatically fetch dependencies. The full documentation is located on [Composer's website](https://getcomposer.org/). Copied from their instructions, you can simply run the following in the terminal.
```
curl -sS https://getcomposer.org/installer | php
```
This will create the composer.phar script in the current directory. You can run composer.phar from the current directory, however, typically it is moved to /usr/local/bin so it is globally accessible and renamed to just 'composer'. The rest of the instructions will assume you've done this. You can do so by running:
```
sudo mv composer.phar /usr/local/bin/composer
```

Verify that composer is installed by running `composer` in the terminal.

Now, clone the Coagmento repository with the following:

```
git clone git@github.com:InfoSeeking/Coagmento.git
```

This will create the Coagmento directory with the source code. Now, we need to install the dependencies with composer as follows.

```
cd Coagmento/core
composer install
```
After this runs, all of the project dependencies should be installed.

Now we need to tell Laravel about your development environment. In the Coagmento/core directory, there is a [.env.example](https://github.com/InfoSeeking/Coagmento/blob/master/core/.env.example) file. Most importantly, rename this file to .env (without the .example) as follows.

```
mv .env.example .env
```

The .env file ignored by git for obvious security reasons, so it needs to be created. Change the DB values in the .env file to match your database setup. The [Laravel enviroment documentation](http://laravel.com/docs/5.1#environment-configuration) has more information on setting up your environment.

To finalize the enviroment setup, you should set APP\_KEY in .env to a random 32 character string. Laravel provides a shortcut to doing this. While in the Coagmento/core directory in the terminal, run the following.
```
php artisan key:generate
```
This will automatically set the APP\_KEY in your .env file.

Lastly, we need to import the database schema for Coagmento. While in Coagmento/core, run the following.

```
php artisan migrate
```
This should create all of the necessary database tables for Coagmento.

Now you should be ready to go. Run
```
php artisan serve
```
To run the Laravel test server.

### Windows ###

Check the [requirements of Laravel](http://laravel.com/docs/5.1) and ensure that these are satisfied. One potential way to install PHP and MySQL is to install XAMPP (https://www.apachefriends.org/index.html). This will install the Apache web server, PHP, and MariaDB (MySQL compatible database). Afterwards, edit your system PATH variable to add the folders containing php.exe and the mysql.exe. php.exe should be located in `C:\xampp\php` and mysql.exe should be in `C:\xampp\mysql\bin` assuming you installed xampp to `C:\xampp`.

Now, in powershell you should be able to run `php` and `mysql`.

Install Composer via the [Windows installer](https://getcomposer.org/download/).

You should now be able to follow the instructions for OSX and Linux.