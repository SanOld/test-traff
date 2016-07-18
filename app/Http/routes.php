<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

   

       
Route::group(['middleware' => ['web', 'statistics']], function () {
  
    Route::auth();

     Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index')->middleware('auth');
    Route::get('/stat',     'StatController@index')->middleware('auth');
    Route::get('/service',  'ServiceController@index')->middleware('auth');
    Route::get('/contact',  'ContactController@index')->middleware('auth');
    Route::get('/about',    'AboutController@index')->middleware('auth');
    Route::get('/example1', 'Example1Controller@index')->middleware('auth');
    Route::get('/example2', 'Example2Controller@index')->middleware('auth');
    Route::get('/example3', 'Example3Controller@index')->middleware('auth');

    Route::get('/example5', function () {
        return view('example5');
    })->middleware('auth');
    Route::get('/example6', function () {
        return view('example6');
    })->middleware('auth');

});

    Route::post('/stat',     'StatController@filters')->middleware('statistics');


Menu::make('MyNavBar', function($menu){

  $menu->add('Home',    'home');
  
  $menu->add('About',    'about');
  $menu->about->add('Who?', 'example5');
  $menu->about->add('What?', 'example6');
  
  $menu->add('Services',    'service');
  $menu->add('Contact',     'contact');

  $menu->add('Example1',    'example1');
  $menu->add('Example2',     'example2');
  $menu->add('Example3',  'example3');
  
  $menu->add('=Statistics=',  'stat');
  
});





