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

    // organisation module

    Route::get('/show-organisation', function () {
        return view('admin.Organisation.showOrganisation');
    });

    Route::get('/create-organisation', function () {
        return view('admin.Organisation.createOrganisation');
    });


    Route::post('/create-organisation', [App\Http\Controllers\OrganisationController::class, 'storeOrganisation'])->name('create-organisation');
    Route::get('/show-organisation', [App\Http\Controllers\OrganisationController::class, 'showOrganisation'])->name('show-organisation');

    Route::get('/delete-organisation/{id}', [App\Http\Controllers\OrganisationController::class, 'destroyOrganisation'])->name('delete-organisation');

    Route::get('/edit-organisation/{id}', [App\Http\Controllers\OrganisationController::class, 'editOrganisation'])->name('edit-organisation');

    Route::post('/update-organisation/{id}', [App\Http\Controllers\OrganisationController::class, 'updateOrganisation'])->name('update-organisation');

    // file upload module

    Route::get('/upload-file', function () {
        return view('admin.Files.uploadFile');
    });

    Route::post('/upload-file', [App\Http\Controllers\FileUploadController::class, 'storeFiles'])->name('upload-file');

    Route::get('/download/{id}', [App\Http\Controllers\FileUploadController::class, 'downloadFile'])->name('download.file');

    Route::get('/show-files', [App\Http\Controllers\FileUploadController::class, 'showFile'])->name('show-files');

    Route::get('/delete-file/{id}', [App\Http\Controllers\FileUploadController::class, 'destroyFile'])->name('delete-file');


});
?>