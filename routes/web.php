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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register', function () {
    return view('auth.login');
});

Route::get('periodo/getByPeriodo/', 'PeriodoController@getByPeriodo');

Route::post('lancamento/createAno/', 'LancamentoController@create')->name('createAno');

Route::get('lancamento/{ano}/ano/', 'LancamentoController@create');

Route::get('/export/{proj}&{checkbox}', 'RelatorioController@export')->name('relatorioExportar'); // exportar relatorio

Route::get('/perfil', 'LoginController@perfil')->name('perfil');
Route::post('perfil/senha', 'LoginController@senha');


Route::Resource('usuario', 'UsuarioController');
Route::Resource('projeto', 'ProjetoController');
Route::Resource('periodo', 'PeriodoController');
Route::Resource('lancamento', 'LancamentoController');
Route::Resource('equipe', 'EquipeController');
Route::Resource('relatorio', 'RelatorioController');
