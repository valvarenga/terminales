<?php

use App\Http\Controllers\Departamento;
use App\Http\Controllers\Terminal;
use App\Http\Controllers\Municipio;
use App\Http\Controllers\AutobusController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AnunciosController;
use App\Http\Controllers\EnlacesController;
use App\Http\Controllers\PeticionAjaxController;
use App\Models\Departamentos;
use App\Http\Controllers\BuscarController;
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
//Ruta principal
Route::get('/',[IndexController::class,'index'])->name('home');


//Ruta Departamentos de la vista principal
Route::get('departamentos',[Departamento::class,'listar'])->name('departamentos.listar'); //Lista los departamentos
Route::get('departamento/{departamento}',[Departamento::class,'departamentos_municipios'])->name('departamentos.municipios'); //Ruta para mostrar los municipios de cada departamento
Route::get('/departamento/{departamento}/municipio/{municipio:slug}',[Departamento::class,'departamento_terminales'])->name('departamento.terminales'); //Ruta para mostrar las terminales del municipio
Route::get('/departamento/terminal/{terminal?}',[Departamento::class,'buscar_autobuses'])->name('departamento.autobuses'); //Obtiene los autobuses de la terminal de cada municipio

//Ruras departamentos CRUD
Route::get('newdepartamento',[Departamento::class,'index'])->name('newdepartamento'); //Ruta para mostrar el formulario de crear departamento
Route::post('departamento',[Departamento::class,'store'])->name('departamento.store'); //Ruta para guardar el departamento
Route::get('departamento',[Departamento::class,'show'])->name('departamentos.show'); //Ruta para mostrar los departamento
Route::get('departamento/{departamento:slug?}',[Departamento::class,'ver_departamento'])->name('departamento.ver'); //Ruta para mostrar un departamento
Route::put('departamento/{departamento:id?}',[Departamento::class,'update'])->name('departamento.update'); //Ruta para actualizar un departamento
Route::delete('departamento/{departamento:id?}',[Departamento::class,'destroy'])->name('departamento.destroy'); //Ruta para eliminar un departamento


//Ruta de municipios
Route::get('newmunicipio',[Municipio::class,'index'])->name('newmunicipio');
Route::post('municipio',[Municipio::class,'store'])->name('municipio.store');
Route::get('municipio',[Municipio::class,'show'])->name('municipio.show');
Route::get('municipio/{municipio}/edit',[Municipio::class,'edit'])->name('municipio.edit');
Route::put('municipio/{municipio}',[Municipio::class,'update'])->name('municipio.update');
Route::delete('municipio/{municipio}',[Municipio::class,'destroy'])->name('municipio.destroy');

Route::get('search/municipios', [Municipio::class, 'search'])->name('municipios.search');


Route::get('municipio/{municipio}',[Municipio::class,'ver'])->name('municipio.ver');

//Ruta de terminales
Route::get('ruta',[Terminal::class,'index'])->name('ruta.index');

Route::get('newterminal', [Terminal::class,'newterminal'])->name('newterminal');

Route::post('terminal', [Terminal::class,'store'])->name('terminal');

Route::get('show',[Terminal::class,'show'])->name('show_terminal');

Route::get('verterminal/{terminales}',[Terminal::class,'verterminal'])->name('ver.terminal');

Route::get('show/{terminal?}/edit', [Terminal::class, 'edit'])->name('terminal.edit');

Route::put('show/{terminal}', [Terminal::class, 'update'])->name('terminal.update');

Route::delete('delete/{terminales}',[Terminal::class,'destroy'])->name('terminal.destroy');

//Ruta de autobuses
Route::get('newautobus', [AutobusController::class,'index'])->name('newbus');
Route::post('autobus', [AutobusController::class,'store'])->name('autobus');

//Ruta de Anuncios
Route::get('anuncios',[AnunciosController::class,'index'])->name('anuncios');

//Ruta de acerca
Route::get('Acerca',[EnlacesController::class,'index'])->name('Acerca');
//Ruta de contacto
//Route::get('contactos',[EnlacesController::class,'show'])->name('contactos');
Route::view('contacto', 'enlaces.contacto')->name('contacto');

//Rutas para hacer peticiones a traves de ajax
Route::get('ajax/{departamento:id?}', [PeticionAjaxController::class,'ajax_municipios'])->name('municipio.ajax');
//Ruta de resultado de Busqueda
Route::get('buscar',[BuscarController::class,'index'])->name('buscar.index');
