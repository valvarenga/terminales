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
use App\Http\Controllers\SugerenciaTerminalController;
use App\Http\Controllers\Terminal;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::post('/sugerencias-terminales', [SugerenciaTerminalController::class, 'store'])->middleware('throttle:terminal-suggestions')->name('sugerencias-terminales.store');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:admin-login')->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('admin')->name('admin.logout');
Route::middleware(['admin', 'admin.transaction'])->group(function () {
    Route::get('/admin', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/sugerencias-terminales', [\App\Http\Controllers\SugerenciaRevisionController::class, 'index'])->name('admin.suggestions.index');
    Route::patch('/admin/sugerencias-terminales/{sugerencia}', [\App\Http\Controllers\SugerenciaRevisionController::class, 'revisar'])->name('admin.suggestions.review');
    Route::middleware('admin.only')->group(function () {
        Route::get('/admin/historial', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('admin.history');
        Route::get('/admin/usuarios', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/usuarios', [\App\Http\Controllers\AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/usuarios/{user}/editar', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/usuarios/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
    });
    Route::get('/admin/sugerencias-terminales/{sugerencia}/foto', [AdminAuthController::class, 'suggestionPhoto'])->name('admin.suggestions.photo');


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
    Route::get('autobuses', [AutobusController::class, 'list'])->name('autobuses.list');
    Route::post('autobus', [AutobusController::class, 'store'])->name('autobus');
    Route::get('autobus/{autobus}', [AutobusController::class, 'show'])->name('autobus.show');
    Route::get('autobus/{autobus}/edit', [AutobusController::class, 'edit'])->name('autobus.edit');
    Route::put('autobus/{autobus}', [AutobusController::class, 'update'])->name('autobus.update');
    Route::delete('autobus/{autobus}', [AutobusController::class, 'destroy'])->name('autobus.destroy');
});

// Consulta pública: departamento -> municipio -> terminal -> horarios.
Route::get('departamentos', [Departamento::class, 'listar'])->name('departamentos.listar');
Route::get('departamento/{departamento}', [Departamento::class, 'departamentos_municipios'])->name('departamentos.municipios');
Route::get('departamento/{departamento}/municipio/{municipio:slug}', [Departamento::class, 'departamento_terminales'])->name('departamento.terminales');
Route::get('terminal/{terminal}/autobuses', [Departamento::class, 'buscar_autobuses'])->name('departamento.autobuses');
Route::match(['get', 'post'], 'rutas', [BuscarController::class, 'index'])->name('buscar.index');
Route::get('search/municipios', [Municipio::class, 'search'])->name('municipios.search');

Route::get('ajax/{departamento_id}', [PeticionAjaxController::class, 'ajax_municipios'])->name('municipio.ajax');
Route::get('anuncios', [AnunciosController::class, 'index'])->name('anuncios');
Route::get('Acerca', [EnlacesController::class, 'index'])->name('Acerca');
Route::view('contacto', 'enlaces.contacto')->name('contacto');
