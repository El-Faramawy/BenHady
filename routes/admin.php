<?php

use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware('web')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::get('login', 'AuthController@index')->name('login');
    Route::post('post_login', 'AuthController@login')->name('admin.post_login');


    //******* after login *******
    Route::group(['middleware' => 'admin'], function () {

        Route::get('logout', 'AuthController@logout')->name('admin.logout');

        Route::get('/', 'HomeController@index')->name('/');
        Route::get('home', 'HomeController@index')->name('admin.home');

        ################################### Profile ##########################################
        Route::get('admin_profile', 'AdminController@profile')->name('admin_profile');
        Route::post('update-profile', 'AdminController@update_profile')->name('admin_profile.update');

        ################################### Admins ##########################################
        Route::resource('admins', 'AdminController');
        Route::post('multi_delete_admins', 'AdminController@multiDelete')->name('admins.multiDelete');

        ################################### users ##########################################
        Route::resource('users', 'UserController');
        Route::post('multi_delete_users', 'UserController@multiDelete')->name('users.multiDelete');

        Route::resource('user_invite', 'UserInviteController');
        Route::post('multi_delete_user_invite', 'UserInviteController@multidelete')->name('user_invite.multiDelete');

        ################################### customers ##########################################
        Route::resource('admin_customers', 'CustomerController');
        Route::post('multi_delete_admin_customers', 'CustomerController@multidelete')->name('admin_customers.multiDelete');

        ################################### brands ##########################################
        Route::resource('brands', 'BrandController');
        Route::post('multi_delete_brands', 'BrandController@multiDelete')->name('brands.multiDelete');
        Route::get('brand/status/{id}', 'BrandController@change_status');

        ################################### brand_subscriptions ##########################################
        Route::resource('brand_subscriptions', 'BrandSubscriptionController');
        Route::post('multi_delete_brand_subscriptions', 'BrandSubscriptionController@multiDelete')->name('brand_subscriptions.multiDelete');

        ################################### duets ##########################################
        Route::resource('duets', 'DuetController');
        Route::post('multi_delete_duets', 'DuetController@multiDelete')->name('duets.multiDelete');

        ################################### ads ##########################################
        Route::resource('ads', 'AdController');
        Route::post('multi_delete_ads', 'AdController@multiDelete')->name('ads.multiDelete');

        ################################### categories ##########################################
        Route::resource('categories', 'CategoryController');
        Route::post('multi_delete_categories', 'CategoryController@multiDelete')->name('categories.multiDelete');

        ################################### answers ##########################################
        Route::resource('answers', 'AnswerController');
        Route::post('multi_delete_answers', 'AnswerController@multiDelete')->name('answers.multiDelete');

        ################################### questions ##########################################
        Route::resource('questions', 'QuestionController');
        Route::post('multi_delete_questions', 'QuestionController@multiDelete')->name('questions.multiDelete');

        ################################### countries ##########################################
        Route::resource('countries', 'CountryController');
        Route::post('multi_delete_countries', 'CountryController@multiDelete')->name('countries.multiDelete');

        ################################### cities ##########################################
        Route::resource('cities', 'CityController');
        Route::post('multi_delete_cities', 'CityController@multiDelete')->name('cities.multiDelete');

        ################################### packages ##########################################
        Route::resource('packages', 'PackageController');
        Route::post('multi_delete_packages', 'PackageController@multiDelete')->name('packages.multiDelete');

        ################################### settings ##########################################
        Route::resource('settings', 'SettingController');

        ################################### profile ##########################################
        Route::resource('profile', 'ProfileController');

    });


//    Route::fallback(function () {
//        return redirect('admin/home');
//    });
    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return '<h1> cache cleared</h1>';
    });
    Route::get('/migrate', function () {
        Artisan::call('migrate ');
//        Artisan::call('db:seed');
        return '<h1> migrated</h1>';
    });
    Route::fallback(function () {
        return redirect('admin/home');
    });
});//end Prefix
