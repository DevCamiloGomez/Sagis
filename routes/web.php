<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\GraduateController;
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

Route::redirect('/', 'home');

// Rutas de login para usuarios normales
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('loggin');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de login para administradores
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.loggin');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

Route::get('profile', [UserController::class, 'profile'])->name('profile');

Route::get('profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::patch('profile/update', [UserController::class, 'update'])->name('profile.update');

Route::get('profile/edit_password', [UserController::class, 'edit_password'])->name('profile.edit_password');
Route::patch('profile/update_password', [UserController::class, 'update_password'])->name('profile.update_password');

Route::get('profile/validate', [UserController::class, 'validate_person'])->name('validate_person');


Route::get('academics', [UserController::class, 'show_academics'])->name('academics');

Route::get('academic_studies/create', [UserController::class, 'create_academic'])->name('create_academic');
Route::post('academic_studies/store', [UserController::class, 'store_academic'])->name('store_academic');

Route::get('academic_studies/{academic}/edit', [UserController::class, 'edit_academic'])->name('edit_academic');
Route::get('academic_studies/{academic}/edit_first', [UserController::class, 'edit_academic_first'])->name('edit_academic_first');
Route::patch('academic_studies/{academic}/update', [UserController::class, 'update_academic'])->name('update_academic');
Route::patch('academic_studies/{academic}/update_first', [UserController::class, 'update_academic_first'])->name('update_academic_first');

Route::delete('academic_studies/{academic}/destroy', [UserController::class, 'destroy_academic'])->name('destroy_academic');

Route::get('academic_studies/validate', [UserController::class, 'validate_academic'])->name('validate_academic');


Route::get('jobs', [UserController::class, 'show_jobs'])->name('jobs');

Route::get('company_jobs/create', [UserController::class, 'create_jobs'])->name('create_jobs');
Route::post('company_jobs/store', [UserController::class, 'store_jobs'])->name('store_jobs');

Route::get('company_jobs/{academic}/edit', [UserController::class, 'edit_jobs'])->name('edit_jobs');
Route::patch('company_jobs/{academic}/update', [UserController::class, 'update_jobs'])->name('update_jobs');

Route::delete('company_jobs/{academic}/destroy', [UserController::class, 'destroy_jobs'])->name('destroy_jobs');

Route::get('company_jobs/validate', [UserController::class, 'validate_jobs'])->name('validate_jobs');


Route::get('home', [HomeController::class, 'home'])->name('home');

Route::get('notices', [HomeController::class, 'notices'])->name('notices');
Route::get('notices/{id}', [HomeController::class, 'showNotice'])->name('notices.show');

Route::get('courses', [HomeController::class, 'courses'])->name('courses');
Route::get('courses/{id}', [HomeController::class, 'showCourse'])->name('courses.show');

Route::get('events', [HomeController::class, 'events'])->name('events');
Route::get('events/{id}', [HomeController::class, 'showEvent'])->name('events.show');

Route::get('gallerys', [HomeController::class, 'gallerys'])->name('gallerys');
Route::get('gallerys/{id}', [HomeController::class, 'showGallery'])->name('gallerys.show');

Route::get('videos', [HomeController::class, 'videos'])->name('videos');
Route::get('videos/{id}', [HomeController::class, 'showVideo'])->name('videos.show');



/** Commands  */

Route::prefix('commands')->group(function () {
    Route::get('optimize', [CommandController::class, 'runOptimize']);
    Route::get('migrate', [CommandController::class, 'runMigrateFresh']);
    Route::get('storage-link', [CommandController::class, 'storageLink']);
});

// Rutas del panel de administración
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Eliminar las rutas del carrusel que ya están en admin.php
});

// Rutas para Geonames
Route::prefix('api/geonames')->group(function () {
    Route::get('/countries', [App\Http\Controllers\GeonamesController::class, 'getCountries']);
    Route::get('/states', [App\Http\Controllers\GeonamesController::class, 'getStates']);
    Route::get('/cities', [App\Http\Controllers\GeonamesController::class, 'getCities']);
    Route::get('/status', [App\Http\Controllers\GeonamesController::class, 'checkAccountStatus']);
});

// Rutas para envío masivo de correos
Route::get('/admin/graduates/mass-email', [App\Http\Controllers\Admin\GraduateController::class, 'showMassEmailForm'])
    ->name('admin.graduates.mass-email-form')
    ->middleware(['auth:admin']);
Route::post('/admin/graduates/mass-email', [App\Http\Controllers\Admin\GraduateController::class, 'sendMassEmail'])
    ->name('admin.graduates.send-mass-email')
    ->middleware(['auth:admin']);

// Ruta para eliminar los primeros 110 graduados
Route::get('/admin/graduates/destroy-first', [App\Http\Controllers\Admin\GraduateController::class, 'destroy_first_graduates'])
    ->name('admin.graduates.destroy-first')
    ->middleware(['auth:admin']);
