<?php

use App\Http\Controllers\GstController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::group(['middleware' => ['auth', 'web']], function() {

Route::get('/home',function(){
 return view('admin.common.main');
});



Route::get('/create-user' , function(){
return view('admin.User.createUser');
});

Route::post('/create-user', [App\Http\Controllers\UsersController::class, 'storeUser'])->name('create-user');

Route::get('/show-user', [App\Http\Controllers\UsersController::class, 'showUser'])->name('show-user');

Route::get('/edit-user/{id}' , [App\Http\Controllers\UsersController::class, 'editUser'])->name('edit-user');

Route::post('/update-user/{id}' , [App\Http\Controllers\UsersController::class, 'updateUser']);

Route::post('/update-user/{id}' , [App\Http\Controllers\UsersController::class, 'updateUser']);
Route::get('/delete-user/{id}' , [App\Http\Controllers\UsersController::class, 'destroyUser'])->name('delete-user');


});
?>