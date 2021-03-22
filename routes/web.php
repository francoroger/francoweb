<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Auth
Auth::routes();

// Home - Dashboard
Route::get('/', 'HomeController@index')->name('home');

// PHP Info
Route::get('phpinfo', function() { phpinfo(); })->name('phpinfo');

// Gerar Thumb
Route::get('thumbnail', 'HomeController@thumbnail')->name('thumbnail');

// Teste Webcam
Route::get('webcam', 'HomeController@webcam')->name('webcam');
Route::post('webcam', 'HomeController@upload')->name('webcam.upload');

// Cadastros
Route::group(['prefix' => 'cadastros'], function() {
  // Clientes
  Route::get('clientes/ajax', 'ClienteController@ajax')->name('clientes.ajax');
  Route::resource('clientes', 'ClienteController');

  // Fornecedores
  Route::get('fornecedores/ajax', 'FornecedorController@ajax')->name('fornecedores.ajax');
  Route::resource('fornecedores', 'FornecedorController');

  // Guias
  Route::get('guias/ajax', 'GuiaController@ajax')->name('guias.ajax');
  Route::resource('guias', 'GuiaController');

  // Cores
  Route::get('cores', 'CorController@index')->name('cores.index');
  Route::post('cores', 'CorController@store')->name('cores.store');
  Route::put('cores/{id}', 'CorController@update')->name('cores.update');
  Route::delete('cores/{id}', 'CorController@destroy')->name('cores.destroy');

  // Materiais
  Route::get('materiais/ajax', 'MaterialController@ajax')->name('materiais.ajax');
  Route::get('materiais/cores_disponiveis/{id}', 'MaterialController@cores_disponiveis')->name('materiais.cores_disponiveis');
  Route::get('materiais/{id}/cotacao', 'MaterialController@cotacao')->name('materiais.cotacao');
  Route::get('materiais/{id}/cotacoes', 'CotacaoController@index')->name('materiais.cotacoes');
  Route::post('materiais/cotacoes', 'CotacaoController@store')->name('cotacoes.store');
  Route::delete('materiais/cotacoes/{id}', 'CotacaoController@destroy')->name('cotacoes.destroy');
  Route::resource('materiais', 'MaterialController');

  // Produtos
  Route::get('produtos', 'ProdutoController@index')->name('produtos.index');
  Route::post('produtos', 'ProdutoController@store')->name('produtos.store');
  Route::put('produtos/{id}', 'ProdutoController@update')->name('produtos.update');
  Route::delete('produtos/{id}', 'ProdutoController@destroy')->name('produtos.destroy');

  //Roles
  Route::get('roles', 'RoleController@index')->name('roles.index');
  Route::get('roles/ajax', 'RoleController@ajax')->name('roles.ajax');
  Route::get('roles/create', 'RoleController@create')->name('roles.create');
  Route::post('roles', 'RoleController@store')->name('roles.store');
  Route::get('roles/{id}', 'RoleController@show')->name('roles.show');
  Route::put('roles/{id}', 'RoleController@update')->name('roles.update');
  Route::get('roles/{id}/edit', 'RoleController@edit')->name('roles.edit');
  Route::delete('roles/{id}', 'RoleController@destroy')->name('roles.destroy');

  // Tanques
  Route::get('tanques/ajax', 'TanqueController@ajax')->name('tanques.ajax');
  Route::resource('tanques', 'TanqueController');

  // Tipos de Serviço
  Route::get('tipos_servico', 'TipoServicoController@index')->name('tipos_servico.index');
  Route::post('tipos_servico', 'TipoServicoController@store')->name('tipos_servico.store');
  Route::put('tipos_servico/{id}', 'TipoServicoController@update')->name('tipos_servico.update');
  Route::delete('tipos_servico/{id}', 'TipoServicoController@destroy')->name('tipos_servico.destroy');

  // Tipos de Transporte
  Route::get('tipos_transporte', 'TipoTransporteController@index')->name('tipos_transporte.index');
  Route::post('tipos_transporte', 'TipoTransporteController@store')->name('tipos_transporte.store');
  Route::put('tipos_transporte/{id}', 'TipoTransporteController@update')->name('tipos_transporte.update');
  Route::delete('tipos_transporte/{id}', 'TipoTransporteController@destroy')->name('tipos_transporte.destroy');

  // Usuários
  Route::get('usuarios/ajax', 'UserController@ajax')->name('usuarios.ajax');
  Route::resource('usuarios', 'UserController');
});

// Catalogação
Route::group(['prefix' => 'catalogacao'], function() {
  //Check list catalogação
  Route::any('checklist/ajax', 'CheckListCatalogacaoController@ajax')->name('catalogacao_checklist.ajax');
  Route::get('checklist', 'CheckListCatalogacaoController@index')->name('catalogacao_checklist.index');
  Route::post('checklist', 'CheckListCatalogacaoController@autosave')->name('catalogacao_checklist.autosave');
  Route::get('checklist/{id}', 'CheckListCatalogacaoController@show')->name('catalogacao_checklist.show');
  Route::get('checklist/{id}/check', 'CheckListCatalogacaoController@check')->name('catalogacao_checklist.check');
  Route::put('checklist/{id}', 'CheckListCatalogacaoController@update')->name('catalogacao_checklist.update');
  Route::post('checklist/{id}/print', 'CheckListCatalogacaoController@print')->name('catalogacao_checklist.print');

  //Relatório de Checklist
  Route::get('relatorio_checklist', 'RelatorioCheckListController@index')->name('relatorio_checklist.index');
  Route::post('relatorio_checklist', 'RelatorioCheckListController@preview')->name('relatorio_checklist.preview');
  Route::post('relatorio_checklist/print', 'RelatorioCheckListController@print')->name('relatorio_checklist.print');
});

// Painel
Route::get('/painel', 'PainelAcompanhamentoController@index')->name('painel');
Route::post('/painel/catalogacao', 'PainelAcompanhamentoController@move')->name('painel.move');
Route::post('/painel/arquivar', 'PainelAcompanhamentoController@arquivar')->name('painel.arquivar');
Route::post('/painel/encerrar_separacao', 'PainelAcompanhamentoController@encerrarSeparacao')->name('painel.encerrar_separacao');
Route::post('/painel/iniciar_banho', 'PainelAcompanhamentoController@iniciarBanho')->name('painel.iniciar_banho');
Route::post('/painel/iniciar_expedicao', 'PainelAcompanhamentoController@iniciarExpedicao')->name('painel.iniciar_expedicao');
Route::get('/painel/column', 'PainelAcompanhamentoController@column')->name('painel.column');

// Recebimentos
Route::get('recebimentos/ajax', 'RecebimentoController@ajax')->name('recebimentos.ajax');
Route::post('recebimentos/upload', 'RecebimentoController@upload')->name('recebimentos.upload');
Route::delete('recebimentos/fotos/{id}', 'RecebimentoController@destroyFoto')->name('recebimentos.destroyFoto');
Route::resource('recebimentos', 'RecebimentoController');

//Produção
Route::group(['prefix' => 'producao'], function() {
  // Reforço
  Route::get('/controle_reforco', 'ReforcoController@index')->name('controle_reforco');
  Route::get('/controle_reforco/consulta', 'ReforcoController@consulta')->name('controle_reforco.consulta');
  Route::delete('controle_reforco/consulta/{id}', 'ReforcoController@destroy')->name('controle_reforco.destroy');
  Route::post('tanques', 'ReforcoController@tanques')->name('api_tanques');
  Route::post('registra_ciclo', 'ReforcoController@registra_ciclo')->name('api_tanques.registrar');
  Route::post('reset_ciclo', 'ReforcoController@reset_ciclo')->name('api_tanques.reforco');
  Route::post('undo_reforco', 'ReforcoController@undo_reforco')->name('api_tanques.undo');
  Route::post('reset_tanque', 'ReforcoController@reset_tanque')->name('api_tanques.reset');
  Route::post('reforco_analise', 'ReforcoController@reforco_analise')->name('api_tanques.reforco_analise');

  //Relatório Ficha de Produção
  Route::get('ficha_producao', 'RelatorioFichaProducaoController@index')->name('relatorio_ficha_producao.index');
  Route::post('ficha_producao', 'RelatorioFichaProducaoController@preview')->name('relatorio_ficha_producao.preview');
  Route::post('ficha_producao/print', 'RelatorioFichaProducaoController@print')->name('relatorio_ficha_producao.print');

  //Relatório de Produção
  Route::get('relatorio_producao', 'RelatorioProducaoController@index')->name('relatorio_producao.index');
  Route::post('relatorio_producao', 'RelatorioProducaoController@preview')->name('relatorio_producao.preview');
  Route::post('relatorio_producao/print', 'RelatorioProducaoController@print')->name('relatorio_producao.print');
});

// Relatórios
Route::group(['prefix' => 'relatorios'], function() {
  Route::get('servicos', 'RelatorioServicoController@index')->name('relatorio_servicos.index');
  Route::post('servicos', 'RelatorioServicoController@preview')->name('relatorio_servicos.preview');
  Route::post('servicos/print', 'RelatorioServicoController@print')->name('relatorio_servicos.print');

  Route::get('tempo_execucao', 'RelatorioTempoExecucaoController@index')->name('relatorio_tempo_execucao.index');
  Route::post('tempo_execucao', 'RelatorioTempoExecucaoController@preview')->name('relatorio_tempo_execucao.preview');
  Route::post('tempo_execucao/print', 'RelatorioTempoExecucaoController@print')->name('relatorio_tempo_execucao.print');
});
