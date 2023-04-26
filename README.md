# Laravel-Scout-Algolia-Search

[![Latest Stable Version](http://poser.pugx.org/phpunit/phpunit/v)](https://packagist.org/packages/phpunit/phpunit) [![Total Downloads](http://poser.pugx.org/phpunit/phpunit/downloads)](https://packagist.org/packages/phpunit/phpunit) [![Latest Unstable Version](http://poser.pugx.org/phpunit/phpunit/v/unstable)](https://packagist.org/packages/phpunit/phpunit) [![License](http://poser.pugx.org/phpunit/phpunit/license)](https://packagist.org/packages/phpunit/phpunit) [![PHP Version Require](http://poser.pugx.org/phpunit/phpunit/require/php)](https://packagist.org/packages/phpunit/phpunit)

## Installation
1)laravel/scout

2)algolia/algoliasearch-client-php

Require this package, with [Composer], in the root directory of your project.

```bash
$ composer require laravel/scout
```

## Configuration

Laravel requires connection configuration. To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

You are free to change the configuration file as needed in .env file:

```php

SCOUT_QUEUE=true

```
Ok, now we have to install package for "algolia", so let's run bellow command:

```bash
$ composer require algolia/algoliasearch-client-php
```

#### Package Configration 
Ok, now we have to set id and secret of algolia, so first you have to create new account in algolia.com. So if you haven't account on algolia.com site then click here and create new account https://www.algolia.com

Ok, after login we have to get application id and secret id
Now open your .env file and paste id and secret like as bellow:

```php

ALGOLIA_APP_ID=paste app id
ALGOLIA_SECRET=paste app secret

```

#### Create Item Table and Model

In this step we have to create migration for items table using Laravel php artisan command, so first fire bellow command:

```bash

$ php artisan make:migration create_items_table

```

After this command you will find one file in following path database/migrations and you have to put bellow code in your migration file for create items table.

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
           $table->increments('id');
           $table->string('title');
           $table->timestamps();
       });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("items");
    }
}
```

#### Run APIs in Postman

import postman collection via link and run APIs

```
https://api.postman.com/collections/22576705-1d39a521-38be-4650-a30e-38d422ef066f?access_key=PMAT-01GYVCCTZDN7SRWWY0XQKEQGDT
```
