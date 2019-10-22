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
Route::get('thumbnail', 'HomeController@thumbnail')->name('thumbnail');
Route::get('webcam', 'HomeController@webcam')->name('webcam');
Route::post('webcam', 'HomeController@upload')->name('webcam.upload');
Route::get('phpinfo', function() { phpinfo(); })->name('phpinfo');

Route::group(['prefix' => 'cadastros'], function() {
  //Clientes
  Route::get('clientes/ajax', 'ClienteController@ajax')->name('clientes.ajax');
  Route::resource('clientes', 'ClienteController');

  //Fornecedores
  Route::get('fornecedores/ajax', 'FornecedorController@ajax')->name('fornecedores.ajax');
  Route::resource('fornecedores', 'FornecedorController');

  //Guias
  Route::get('guias/ajax', 'GuiaController@ajax')->name('guias.ajax');
  Route::resource('guias', 'GuiaController');

  //Cores
  Route::get('cores', 'CorController@index')->name('cores.index');
  Route::post('cores', 'CorController@store')->name('cores.store');
  Route::put('cores/{id}', 'CorController@update')->name('cores.update');
  Route::delete('cores/{id}', 'CorController@destroy')->name('cores.destroy');

  //Materiais
  Route::get('materiais/ajax', 'MaterialController@ajax')->name('materiais.ajax');
  Route::get('materiais/{id}/cotacao', 'MaterialController@cotacao')->name('materiais.cotacao');
  Route::get('materiais/{id}/cotacoes', 'CotacaoController@index')->name('materiais.cotacoes');
  Route::post('materiais/cotacoes', 'CotacaoController@store')->name('cotacoes.store');
  Route::delete('materiais/cotacoes/{id}', 'CotacaoController@destroy')->name('cotacoes.destroy');
  Route::resource('materiais', 'MaterialController');

  //Produtos
  Route::get('produtos', 'ProdutoController@index')->name('produtos.index');
  Route::post('produtos', 'ProdutoController@store')->name('produtos.store');
  Route::put('produtos/{id}', 'ProdutoController@update')->name('produtos.update');
  Route::delete('produtos/{id}', 'ProdutoController@destroy')->name('produtos.destroy');

  //Tipos de Serviço
  Route::get('tipos_servico', 'TipoServicoController@index')->name('tipos_servico.index');
  Route::post('tipos_servico', 'TipoServicoController@store')->name('tipos_servico.store');
  Route::put('tipos_servico/{id}', 'TipoServicoController@update')->name('tipos_servico.update');
  Route::delete('tipos_servico/{id}', 'TipoServicoController@destroy')->name('tipos_servico.destroy');

  //Tipos de Transporte
  Route::get('tipos_transporte', 'TipoTransporteController@index')->name('tipos_transporte.index');
  Route::post('tipos_transporte', 'TipoTransporteController@store')->name('tipos_transporte.store');
  Route::put('tipos_transporte/{id}', 'TipoTransporteController@update')->name('tipos_transporte.update');
  Route::delete('tipos_transporte/{id}', 'TipoTransporteController@destroy')->name('tipos_transporte.destroy');

  //Usuários
  Route::get('usuarios/ajax', 'UserController@ajax')->name('usuarios.ajax');
  Route::resource('usuarios', 'UserController');
});

Route::group(['prefix' => 'catalogacao'], function() {
  //Check list catalogação
  Route::get('checklist/ajax', 'CheckListCatalogacaoController@ajax')->name('catalogacao_checklist.ajax');
  Route::get('checklist', 'CheckListCatalogacaoController@index')->name('catalogacao_checklist.index');
  Route::post('checklist', 'CheckListCatalogacaoController@autosave')->name('catalogacao_checklist.autosave');
  Route::get('checklist/{id}', 'CheckListCatalogacaoController@show')->name('catalogacao_checklist.show');
  Route::get('checklist/{id}/check', 'CheckListCatalogacaoController@check')->name('catalogacao_checklist.check');
  Route::put('checklist/{id}', 'CheckListCatalogacaoController@update')->name('catalogacao_checklist.update');
  Route::get('checklist/{id}/print', 'CheckListCatalogacaoController@print')->name('catalogacao_checklist.print');
});

Route::group(['prefix' => 'relatorios'], function() {
  Route::get('servicos', 'RelatorioServicoController@index')->name('relatorio_servicos.index');
  Route::post('servicos', 'RelatorioServicoController@search')->name('relatorio_servicos.search');
});

//Recebimentos
Route::get('recebimentos/ajax', 'RecebimentoController@ajax')->name('recebimentos.ajax');
Route::resource('recebimentos', 'RecebimentoController');
