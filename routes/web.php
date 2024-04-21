<?php

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

// Rutas para usuarios no autenticados
Route::get('/', [App\Http\Controllers\AdminController::class, 'login'])->name('root');
Route::get('/login', [App\Http\Controllers\AdminController::class, 'login'])->name('login');
Route::post('/login', 'App\Http\Controllers\AdminController@tryLogin')->name('login');

// Rutas para administradores
Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'adminLogin'])->name('admin-login');
Route::post('/admin/login', 'App\Http\Controllers\AdminController@tryAdminLogin')->name('admin-login');

// Rutas para usuarios autenticados
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Cerrar sesión
    Route::post('/logout', 'App\Http\Controllers\AdminController@logout')->name('logout');

    // Rutas de perfil
    Route::get('/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::post('/editName', [App\Http\Controllers\AdminController::class, 'editName'])->name('editName');
    Route::post('/editProfilePhoto', [App\Http\Controllers\AdminController::class, 'editProfilePhoto'])->name('editProfilePhoto');
    Route::post('/changePassword', [App\Http\Controllers\AdminController::class, 'changePassword'])->name('changePassword');

    // Rutas de historias
    Route::get('/stories/{level}', [App\Http\Controllers\StoryController::class, 'show'])->name('stories');
    Route::get('/getLastOrder', [App\Http\Controllers\StoryController::class, 'getLastOrder'])->name('getLastOrder');
    Route::post('/addStory', [App\Http\Controllers\StoryController::class, 'store'])->name('addStory');
    Route::post('/editStory', [App\Http\Controllers\StoryController::class, 'edit'])->name('editStory');
    Route::post('/deleteStory', [App\Http\Controllers\StoryController::class, 'destroy'])->name('deleteStory');
    Route::post('/publishStory', [App\Http\Controllers\StoryController::class, 'publishStory'])->name('publishStory');

    // Rutas de diapositivas
    Route::get('/slide/{story}', [App\Http\Controllers\StoryMediaController::class, 'show'])->name('storyslide');
    Route::post('/addNewSlide', [App\Http\Controllers\StoryMediaController::class, 'store'])->name('addNewSlide');
    Route::post('/editSlideImage', [App\Http\Controllers\StoryMediaController::class, 'editSlideImage'])->name('editSlideImage');
    Route::post('/editSlideAudio', [App\Http\Controllers\StoryMediaController::class, 'editSlideAudio'])->name('editSlideAudio');
    Route::post('/editSlideText', [App\Http\Controllers\StoryMediaController::class, 'editSlideText'])->name('editSlideText');
    Route::post('/deleteSlide', [App\Http\Controllers\StoryMediaController::class, 'destroy'])->name('deleteSlide');
    Route::post('/updateSlideOrder', [App\Http\Controllers\StoryMediaController::class, 'updateSlideOrder'])->name('updateSlideOrder');

    // Rutas de administración (solo para administradores)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Panel de control
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'showChart'])->name('home');

        // Gestión de administradores
        Route::get('/manage', [App\Http\Controllers\AdminController::class, 'show'])->name('manage');
        Route::post('/register', [App\Http\Controllers\AdminController::class, 'store'])->name('register');
        Route::post('/editManager', [App\Http\Controllers\AdminController::class, 'update'])->name('editManager');
        Route::get('/adminChangeLocked', [App\Http\Controllers\AdminController::class, 'adminChangeLocked'])->name('delete');
        Route::post('/deleteAdmin', [App\Http\Controllers\AdminController::class, 'destroy'])->name('delete-admin');
    });
});
