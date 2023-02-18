<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Auth::routes(); // verifikasi email 
Route::get('/', 'HomeController@index')->name('home');

// AJAX user tables
Route::get('/data-user', 'TableController@userDataTables')->name('table.data.user');
Route::get('/data-pembayaran', 'HomeController@paymentDataTables')->name('table.data.payment');
Route::get('/user-result/{user}', 'InfoController@ajaxResult')->name('view.result');



// --------------------------------------------------------- CHANGE PASSWORD 
Route::middleware('auth')->group(function () {
    Route::get('account/password', 'Account\PasswordController@edit')->name('password.edit');
    Route::get('account/profile', 'Account\PasswordController@editProfile')->name('profile.edit');
    Route::patch('account/password', 'Account\PasswordController@update')->name('password.edit');
});


Route::prefix('member')->middleware('can:member-area')->group(function () {
    Route::get('page/info', 'InfoController@showInfo')->name('member.index');
    Route::get('page/q-n-a', 'InfoController@showQuestion')->name('member.view.question');

    Route::post('page/data-info', 'InfoController@postInfo')->name('member.info');
    Route::post('page/kirim-data', 'InfoController@postData')->name('member.data.post');
    Route::get('page/data-success', 'InfoController@suksesData')->name('member.data.sukses');
    Route::get('page/data-ajax', 'InfoController@dataAjax')->name('member.data.ajax');
});

//Providers\AuthServiceProvider  ------------- untuk setting gate nya
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:admin-area')->group(function () {
    Route::resource('/users', 'UsersController');
    Route::post('/update-data', 'UsersController@updateData')->name('users.update.data');
    Route::post('/update-data-soal', 'UsersController@updateSoal')->name('soal.update');
    Route::post('/tambah-data-soal', 'UsersController@tambahSoal')->name('soal.tambah');
    Route::get('/delete-data-soal/{soal}', 'UsersController@deleteSoal')->name('soal.delete');
});

//ACADEMIC
Route::prefix('academic')->name('academic.')->middleware('can:academic-area')->group(function () {
    Route::resource('/users', 'AcademicController');
});

// HALAMAN SOAL
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:admin-area')->group(function () {
    Route::resource('/categories', 'CategoryController');
});
Route::get('/data-soal', 'Admin\CategoryController@soalDataTables')->name('table.data.soal');
Route::get('/total-major', 'Admin\CategoryController@totalMajor');

// HALAMAN SOAL
Route::prefix('admin')->name('admin.')->middleware('can:admin-area')->group(function () {
    Route::resource('/major', 'MajorController');
    Route::post('/update-data-major', 'MajorController@updateMajor')->name('major.update');
    Route::post('/tambah-data-major', 'MajorController@tambahMajor')->name('major.tambah');
    Route::get('/major/active/{major}', 'MajorController@aktifData');
    Route::get('/delete-data-major/{major}', 'MajorController@deleteMajor')->name('major.delete');

    Route::get('/reset-data-master', 'InfoController@reset')->name('reset.data.master');
    Route::get('/hapus-data-master', 'InfoController@hapusData')->name('hapus.data.master');
    Route::get('/reset-data-user/{info}', 'InfoController@resetUser')->name('reset.data.user');
    Route::get('/users-detail/{info}', 'InfoController@getUserDetail');

    // new update route 31012021 
    Route::get('/users-management', 'InfoController@viewUserManagement')->name('user.management');
    Route::get('/table-user-management', 'InfoController@userManagementDataTables')->name('table.user.management');
    Route::get('/get-info-users/{user}', 'InfoController@getUser')->name('get.info.user');
    Route::post('/user-execution', 'InfoController@userExecution')->name('user.execution');
    Route::post('/add-user-execution', 'InfoController@userExecutionAdd')->name('add.user.execution');
    Route::get('/delete-info-users/{user}', 'InfoController@userExecutionDelete')->name('delete.user.execution');
});
Route::get('/data-major', 'MajorController@majorDataTables')->name('table.data.major');

//UPLOAD DATA 
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:admin-area')->group(function () {
    Route::get('upload', 'UsersController@viewUpload')->name('view.upload');
    Route::post('upload', 'UsersController@postUpload')->name('post.upload');
    Route::get('/user/excel', 'UsersController@export_excel')->name('export.excel');
});
