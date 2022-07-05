<?php

use App\Http\Controllers\Departamento;
use App\Http\Controllers\Terminal;
use App\Http\Controllers\Municipio;
use App\Http\Controllers\AutobusController;
use App\Http\Controllers\IndexController;
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

Route::get('newdepartamento',[Departamento::class,'index'])->name('newdepartamento');
Route::post('departamento',[Departamento::class,'store'])->name('departamento.store');

//Ruta de municipios
Route::get('newmunicipio',[Municipio::class,'index'])->name('newmunicipio');
Route::post('municipio',[Municipio::class,'store'])->name('municipio.store');
Route::get('municipio',[Municipio::class,'show'])->name('municipio.show');

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
