<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GraduateController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\CarouselController;


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

// Route::middleware('guest:admin')->group(function () {
Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('login', [LoginController::class, 'login'])->name('admin.loggin');
// });

Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('home', [HomeController::class, 'home'])->name('admin.home');

Route::resource('graduates', GraduateController::class, ['as' => 'admin']);

Route::post('graduates/import_excel', [GraduateController::class, 'import_excel'])->name('admin.graduates.import_excel');
Route::post('graduates/destroy_all', [GraduateController::class, 'destroy_all'])->name('admin.graduates.destroy_all');
Route::post('graduates/send_email', [GraduateController::class, 'send_email'])->name('admin.graduates.send_email');


Route::get('graduates/{graduate}/edit-password', [GraduateController::class, 'edit_password'])->name('admin.graduates.edit_password');
Route::patch('graduates/{graduate}/update-password', [GraduateController::class, 'update_password'])->name('admin.graduates.update_password');

Route::get('graduates/{graduate}/academic_studies/create', [GraduateController::class, 'create_academic'])->name('admin.graduates.create_academic');
Route::post('graduates/{graduate}/academic_studies/store', [GraduateController::class, 'store_academic'])->name('admin.graduates.store_academic');

Route::get('graduates/{graduate}/academic_studies/{academic}/edit', [GraduateController::class, 'edit_academic'])->name('admin.graduates.edit_academic');
Route::patch('graduates/{graduate}/academic_studies/{academic}/update', [GraduateController::class, 'update_academic'])->name('admin.graduates.update_academic');

Route::delete('graduates/{graduate}/academic_studies/{academic}/destroy', [GraduateController::class, 'destroy_academic'])->name('admin.graduates.destroy_academic');

Route::delete('graduates/{graduate}',[GraduateController::class, 'destroy'])->name('admin.graduates.destroy');

Route::get('graduates/{graduate}/company_jobs/create', [GraduateController::class, 'create_jobs'])->name('admin.graduates.create_jobs');
Route::post('graduates/{graduate}/company_jobs/store', [GraduateController::class, 'store_jobs'])->name('admin.graduates.store_jobs');

Route::get('graduates/{graduate}/company_jobs/{job}/edit', [GraduateController::class, 'edit_jobs'])->name('admin.graduates.edit_jobs');
Route::patch('graduates/{graduate}/company_jobs/{job}/update', [GraduateController::class, 'update_jobs'])->name('admin.graduates.update_jobs');

Route::delete('graduates/{graduate}/company_jobs/{job}/destroy', [GraduateController::class, 'destroy_jobs'])->name('admin.graduates.destroy_jobs');

Route::resource('posts', PostController::class, ['as' => 'admin']);



Route::get('posts/{post}/images', [PostController::class, 'images'])->name('admin.posts.images');
Route::patch('posts/{post}/update_images', [PostController::class, 'update_images'])->name('admin.posts.update_images');


Route::get('posts/{post}/images/create_image', [PostController::class, 'create_image'])->name('admin.posts.create_image');
Route::post('posts/{post}/images/store_image', [PostController::class, 'store_image'])->name('admin.posts.store_image');

Route::get('posts/{post}/images/{image}/edit_image', [PostController::class, 'edit_image'])->name('admin.posts.edit_image');
Route::patch('posts/{post}/images/{image}/update_image', [PostController::class, 'update_image'])->name('admin.posts.update_image');

Route::delete('posts/{post}/images/{image}/destroy_image', [PostController::class, 'destroy_image'])->name('admin.posts.destroy_image');

Route::post('posts/destroy_all', [PostController::class, 'destroy_all'])->name('admin.posts.destroy_all');

//Route::get('graduates/{graduate}/academic_studies/{academic}/edit', [GraduateController::class, 'edit_academic'])->name('admin.graduates.edit_academic');

Route::prefix('reports')->group(function () {
    Route::get('graduates', [ReportController::class, 'graduates'])->name('admin.reports.graduates');
    Route::get('graduates/excel', [ExportController::class, 'graduatesExcel'])->name('admin.reports.graduates.excel');
    Route::get('statistics', [ReportController::class, 'statistics'])->name('admin.reports.statistics');
});


Route::get('settings', [AdminController::class, 'settings'])->name('admin.settings');

Route::get('settings/edit', [AdminController::class, 'edit'])->name('admin.settings.edit');
Route::patch('settings/update', [AdminController::class, 'update'])->name('admin.settings.update');

Route::get('settings/edit_password', [AdminController::class, 'edit_password'])->name('admin.settings.edit_password');
Route::patch('settings/update_password', [AdminController::class, 'update_password'])->name('admin.settings.update_password');


Route::get('settings/create_admin', [AdminController::class, 'create_admin'])->name('admin.settings.create_admin');
Route::post('settings/store_admin', [AdminController::class, 'store_admin'])->name('admin.settings.store_admin');


Route::get('settings/{admin}/edit_admin', [AdminController::class, 'edit_admin'])->name('admin.settings.edit_admin');
Route::patch('settings/{admin}/update_admin', [AdminController::class, 'update_admin'])->name('admin.settings.update_admin');

Route::delete('settings/{admin}/destroy_admin', [AdminController::class, 'destroy_admin'])->name('admin.settings.destroy_admin');

Route::get('settings/{admin}/edit_password_admin', [AdminController::class, 'edit_password_admin'])->name('admin.settings.edit_password_admin');
Route::patch('settings/{admin}/update_password_admin', [AdminController::class, 'update_password_admin'])->name('admin.settings.update_password_admin');

Route::get('settings/{admin}/reset_password', [AdminController::class, 'reset_admin_password'])->name('admin.settings.reset_password');

// Rutas para el carrusel
Route::get('/carousel', [CarouselController::class, 'index'])->name('admin.carousel.index');
Route::get('/carousel/create', [CarouselController::class, 'create'])->name('admin.carousel.create');
Route::post('/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store');
Route::get('/carousel/{carousel}/edit', [CarouselController::class, 'edit'])->name('admin.carousel.edit');
Route::put('/carousel/{carousel}', [CarouselController::class, 'update'])->name('admin.carousel.update');
Route::delete('/carousel/{carousel}', [CarouselController::class, 'destroy'])->name('admin.carousel.destroy');
Route::post('/carousel/update-order', [CarouselController::class, 'updateOrder'])->name('admin.carousel.update-order');


