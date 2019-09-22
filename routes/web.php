<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'cadastros'], function() {
  //Clientes
  Route::get('clientes/ajax', 'ClienteController@ajax')->name('clientes.ajax');
  Route::resource('clientes', 'ClienteController');

  //Cores
  Route::get('cores', 'CorController@index')->name('cores.index');
  Route::post('cores', 'CorController@store')->name('cores.store');
  Route::put('cores/{id}', 'CorController@update')->name('cores.update');
  Route::delete('cores/{id}', 'CorController@destroy')->name('cores.destroy');
});

Route::group(['prefix' => 'catalogacao'], function() {
  //Check list catalogação
  Route::get('checklist/ajax', 'CheckListCatalogacaoController@ajax')->name('catalogacao_checklist.ajax');
  Route::get('checklist', 'CheckListCatalogacaoController@index')->name('catalogacao_checklist.index');
  Route::get('checklist/{id}', 'CheckListCatalogacaoController@show')->name('catalogacao_checklist.show');
  Route::get('checklist/{id}/check', 'CheckListCatalogacaoController@check')->name('catalogacao_checklist.check');
  Route::put('checklist/{id}', 'CheckListCatalogacaoController@update')->name('catalogacao_checklist.update');
});
