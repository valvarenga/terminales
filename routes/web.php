<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AnunciosController;
use App\Http\Controllers\AutobusController;
use App\Http\Controllers\BuscarController;
use App\Http\Controllers\Departamento;
use App\Http\Controllers\EnlacesController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Municipio;
use App\Http\Controllers\PeticionAjaxController;
use App\Http\Controllers\Terminal;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');

    // Administración de departamentos.
    Route::get('newdepartamento', [Departamento::class, 'index'])->name('newdepartamento');
    Route::post('departamento', [Departamento::class, 'store'])->name('departamento.store');
    Route::get('departamento', [Departamento::class, 'show'])->name('departamentos.show');
    Route::get('departamentos/{departamento:slug}', [Departamento::class, 'ver_departamento'])->name('departamento.ver');
    Route::put('departamento/{departamento}', [Departamento::class, 'update'])->name('departamento.update');
    Route::delete('departamento/{departamento}', [Departamento::class, 'destroy'])->name('departamento.destroy');

    // Administración de municipios.
    Route::get('newmunicipio', [Municipio::class, 'index'])->name('newmunicipio');
    Route::post('municipio', [Municipio::class, 'store'])->name('municipio.store');
    Route::get('municipio', [Municipio::class, 'show'])->name('municipio.show');
    Route::get('municipio/{municipio}/edit', [Municipio::class, 'edit'])->name('municipio.edit');
    Route::put('municipio/{municipio}', [Municipio::class, 'update'])->name('municipio.update');
    Route::delete('municipio/{municipio}', [Municipio::class, 'destroy'])->name('municipio.destroy');
    Route::get('municipio/{municipio}', [Municipio::class, 'ver'])->name('municipio.ver');

    // Administración de terminales y autobuses.
    Route::get('ruta', [Terminal::class, 'index'])->name('ruta.index');
    Route::get('newterminal', [Terminal::class, 'newterminal'])->name('newterminal');
    Route::post('terminal', [Terminal::class, 'store'])->name('terminal');
    Route::get('show', [Terminal::class, 'show'])->name('show_terminal');
    Route::get('verterminal/{terminales}', [Terminal::class, 'verterminal'])->name('ver.terminal');
    Route::get('show/{terminal}/edit', [Terminal::class, 'edit'])->name('terminal.edit');
    Route::put('show/{terminal}', [Terminal::class, 'update'])->name('terminal.update');
    Route::delete('delete/{terminales}', [Terminal::class, 'destroy'])->name('terminal.destroy');
    Route::get('newautobus', [AutobusController::class, 'index'])->name('newbus');
    Route::post('autobus', [AutobusController::class, 'store'])->name('autobus');
    Route::get('autobus/{autobus}/edit', [AutobusController::class, 'edit'])->name('autobus.edit');
    Route::put('autobus/{autobus}', [AutobusController::class, 'update'])->name('autobus.update');
});

// Consulta pública: departamento -> municipio -> terminal -> horarios.
Route::get('departamentos', [Departamento::class, 'listar'])->name('departamentos.listar');
Route::get('departamento/{departamento}', [Departamento::class, 'departamentos_municipios'])->name('departamentos.municipios');
Route::get('departamento/{departamento}/municipio/{municipio:slug}', [Departamento::class, 'departamento_terminales'])->name('departamento.terminales');
Route::get('terminal/{terminal}/autobuses', [Departamento::class, 'buscar_autobuses'])->name('departamento.autobuses');
Route::match(['get', 'post'], 'rutas', [BuscarController::class, 'index'])->name('buscar.index');
Route::get('search/municipios', [Municipio::class, 'search'])->name('municipios.search');

Route::get('ajax/{departamento}', [PeticionAjaxController::class, 'ajax_municipios'])->name('municipio.ajax');
Route::get('anuncios', [AnunciosController::class, 'index'])->name('anuncios');
Route::get('Acerca', [EnlacesController::class, 'index'])->name('Acerca');
Route::view('contacto', 'enlaces.contacto')->name('contacto');
