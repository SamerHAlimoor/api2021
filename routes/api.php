<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//all routes / api here must be api authenticated

Route::group(['middleware'=>['api','checkPassword','changeLanguge'],'namespace'=>'Api'],(function () {
    
    Route::post('get-main-categories', 'CategoriesController@index');
    Route::post('get-category-byId', 'CategoriesController@getCategoryById');
    Route::post('change-category-status', 'CategoriesController@changeStatus');
   

}));

Route::group(['prefix' => 'admin','namespace'=>'Admin','middleware' => ['api','checkPassword','changeLanguge']],function (){
    Route::post('login','AuthController@Login') ;
    Route::post('logout','AuthController@logout')->middleware("publicGuard:admin-api") ;

});

Route::group(['prefix' => 'user','namespace'=>'User'],function (){
    Route::post('login','AuthControllerUser@Login') ;
    Route::post('logout','AuthControllerUser@logout')->middleware("publicGuard:user-api") ;

});

Route::group(['prefix' => 'user' ,'middleware' => 'publicGuard:user-api'],function (){
    Route::post('profile',function(){
        return  "You are in Your profile Now"; // return authenticated user data
    }) ;


 });


Route::group(['middleware' => ['api','checkPassword','changeLanguage','checkAdminToken:admin-api'], 'namespace' => 'Api'], function () {
    Route::get('offers', 'CategoriesController@index');
});



