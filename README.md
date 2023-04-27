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
Now we have to install package for "algolia", so let's run bellow command:

```bash
$ composer require algolia/algoliasearch-client-php
```

#### Package Configration 
Now we have to set id and secret of algolia, so first you have to create new account in algolia.com. So if you haven't account on algolia.com site then click here and create new account https://www.algolia.com

After login we have to get application id and secret id
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

After create "items" table you should create Item model for items, so first create file in this path app/Item.php and put bellow content in item.php file:
app/Item.php

```php

<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Item extends Model
{


    use Searchable;


    public $fillable = ['title'];


    /**
     * Get the index name for the model.
     *
     * @return string
    */
    public function searchableAs()
    {
        return 'items_index';
    }
}
```
#### Add New Route

In this is step we need to create routes for add new items and listing. so open your routes/web.php file and add following route.
routes/web.php

```php
Route::get('items-lists', 'ItemSearchController@index')->name('items-lists');
Route::post('create-item', 'ItemSearchController@create')->name('create-item');
```
#### Create Controller

In this step, now we should create new controller as ItemSearchController in this path app/Http/Controllers/ItemSearchController.php. this controller will manage all listing items and add new item request and return response, so put bellow content in controller file:

app/Http/Controllers/ItemSearchController.php

```php

<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Item;


class ItemSearchController extends Controller
{


	/**
     * Get the index name for the model.
     *
     * @return string
    */
    public function index(Request $request)
    {
        if($request->has('titlesearch')){
            $items = Item::search($request->titlesearch)
                ->paginate(6);
        }else{
            $items = Item::paginate(6);
        }
        return view('item-search',compact('items'));
    }


    /**
     * Get the index name for the model.
     *
     * @return string
    */
    public function create(Request $request)
    {
        $this->validate($request,['title'=>'required']);


        $items = Item::create($request->all());
        return back();
    }
}
```
#### Create View

In Last step, let's create item-search.blade.php(resources/views/item-search.blade.php) for layout and we will write design code here and put following code:
resources/views/item-search.blade.php

```php
<!DOCTYPE html>
<html>
<head>
    <title>Laravel - laravel scout algolia search example</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>


<div class="container">
    <h2>Laravel Full Text Search using Scout and algolia</h2><br/>


    <form method="POST" action="{{ route('create-item') }}" autocomplete="off">
        @if(count($errors))
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <input type="hidden" name="_token" value="{{ csrf_token() }}">


        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter Title" value="{{ old('title') }}">
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success">Create New Item</button>
                </div>
            </div>
        </div>
    </form>


    <div class="panel panel-primary">
      <div class="panel-heading">Item management</div>
      <div class="panel-body">
            <form method="GET" action="{{ route('items-lists') }}">


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="titlesearch" class="form-control" placeholder="Enter Title For Search" value="{{ old('titlesearch') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>


            <table class="table table-bordered">
                <thead>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Creation Date</th>
                    <th>Updated Date</th>
                </thead>
                <tbody>
                    @if($items->count())
                        @foreach($items as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->updated_at }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">There are no data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $items->links() }}
      </div>
    </div>


</div>


</body>
</html>
```
Now we are ready to run this example so quick run by following command:

```bash
    php artisan serve
```
Now open your browser and run bellow link:
http://localhost:8000/items-lists

I also created some APIs
Add this routes/api.php

```php
Route::post('searchapi',[ItemSearchController::class,'searchapi']);
Route::post('createapi',[ItemSearchController::class,'createapi']);
``` 
Add this functions to your controller

```php
public function searchapi(Request $request)
    {
            try 
            {

                if ($request->has('titlesearch')) {
                    $items = Item::search($request->titlesearch)->get();
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "Record Found",
                            'data' => $items
                        ],
                        200
                    );
                } else {
                    $items = Item::all();
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "All Records",
                            'data' => $items
                        ],
                        200
                    );
                }
            }
            catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => trans('validation.custom.invalid.request'),
                    'error'   => $e->getMessage()
                ], 500);
            }
        // }
    }

    #API Function For Create/Insert a Record

    public function createapi(Request $request)
    {
        $data = $request->only('title');
        $validator = Validator::make($data, [
            'title' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } else {
            try {

                $items = Item::create($request->all());
                if ($items) {
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "Record Created  Successfuly!",
                            'data' => $items
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "Unable To Create Record",

                        ],
                        400
                    );
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => trans('validation.custom.invalid.request'),
                    'error'   => $e->getMessage()
                ], 500);
            }
        }
    }
    
```
#### Run APIs in Postman

import postman collection via link and run APIs

```
https://api.postman.com/collections/22576705-34cbd944-2e40-4355-bba5-5e7e6a640888?access_key=PMAT-01GZ0GCW4Q77HW6XFJET4K7529
```
