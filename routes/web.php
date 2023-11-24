<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::group(['middleware' => ['auth', 'web']], function () {

    Route::get('/home', function () {
        return view('admin.common.main');
    });

// user module

    Route::get('/create-user', function () {
        return view('admin.User.createUser');
    });

    Route::post('/create-user', [App\Http\Controllers\UsersController::class, 'storeUser'])->name('create-user');

    Route::get('/create-user', [App\Http\Controllers\UsersController::class, 'getOrganisationDetails']);

    Route::get('/get-organisation-code/{id}', [App\Http\Controllers\UsersController::class, 'getOrganisationCode'])->name('get-organisation-code');

    Route::post('/store-organisation-data', [App\Http\Controllers\UsersController::class, 'storeOrganisationData'])->name('store-organisation-data');

    Route::get('/show-user', [App\Http\Controllers\UsersController::class, 'showUser'])->name('show-user');

    Route::get('/edit-user/{id}', [App\Http\Controllers\UsersController::class, 'editUser'])->name('edit-user');

    Route::post('/update-user/{id}', [App\Http\Controllers\UsersController::class, 'updateUser']);

    Route::get('/delete-user/{id}', [App\Http\Controllers\UsersController::class, 'destroyUser'])->name('delete-user');

});
?>