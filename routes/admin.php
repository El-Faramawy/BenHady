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
        Route::get('admin_profile', 'ProfileController@index')->name('admin_profile');
        Route::post('update-profile', 'ProfileController@update')->name('admin_profile.update');

        ################################### Admins ##########################################
        Route::resource('admins', 'AdminController');
        Route::post('multi_delete_admins', 'AdminController@multiDelete')->name('admins.multiDelete');

        ################################### Categories ##########################################
        Route::resource('categories', 'CategoryController');
        Route::post('multi_delete_categories', 'CategoryController@multiDelete')->name('categories.multiDelete');

        ################################### Brands ##########################################
        Route::resource('brands', 'BrandController');
        Route::post('multi_delete_brands', 'BrandController@multiDelete')->name('brands.multiDelete');

        ################################### Cities ##########################################
        Route::resource('cities', 'CityController');
        Route::post('multi_delete_cities', 'CityController@multiDelete')->name('cities.multiDelete');

        ################################### Branches ##########################################
        Route::get('branches/{branch}/working-hours', 'BranchWorkingHourController@edit')->name('branches.working_hours.edit');
        Route::put('branches/{branch}/working-hours', 'BranchWorkingHourController@update')->name('branches.working_hours.update');
        Route::resource('branches', 'BranchController');
        Route::post('multi_delete_branches', 'BranchController@multiDelete')->name('branches.multiDelete');

        ################################### Model Years ##########################################
        Route::resource('model_years', 'ModelYearController');
        Route::post('multi_delete_model_years', 'ModelYearController@multiDelete')->name('model_years.multiDelete');

        ################################### Transmissions ##########################################
        Route::resource('transmissions', 'TransmissionController');
        Route::post('multi_delete_transmissions', 'TransmissionController@multiDelete')->name('transmissions.multiDelete');

        ################################### Fuel Types ##########################################
        Route::resource('fuel_types', 'FuelTypeController');
        Route::post('multi_delete_fuel_types', 'FuelTypeController@multiDelete')->name('fuel_types.multiDelete');

        ################################### Features ##########################################
        Route::resource('features', 'FeatureController');
        Route::post('multi_delete_features', 'FeatureController@multiDelete')->name('features.multiDelete');

        ################################### FAQs ##########################################
        Route::resource('faqs', 'FaqController');
        Route::post('multi_delete_faqs', 'FaqController@multiDelete')->name('faqs.multiDelete');

        ################################### Users ##########################################
        Route::resource('users', 'UserController');
        Route::post('multi_delete_users', 'UserController@multiDelete')->name('users.multiDelete');

        ################################### Cars ##########################################
        Route::get('cars/catalog', 'CarController@detailsCatalog')->name('cars.details_catalog');
        Route::post('cars/{car}/delete-image/{imageId}', 'CarController@deleteImage')->name('cars.delete_image');
        Route::post('cars/{car}/set-primary-image/{imageId}', 'CarController@setPrimaryImage')->name('cars.set_primary_image');
        Route::resource('cars', 'CarController');
        Route::post('multi_delete_cars', 'CarController@multiDelete')->name('cars.multiDelete');

        ################################### Bookings ##########################################
        Route::get('bookings/car_availability', 'BookingController@getCarAvailability')->name('bookings.car_availability');
        Route::get('bookings/invoice/{id}', 'BookingController@invoice')->name('bookings.invoice');
        Route::resource('bookings', 'BookingController');
        Route::post('multi_delete_bookings', 'BookingController@multiDelete')->name('bookings.multiDelete');

        ################################### Rental Policies ##########################################
        Route::resource('rental_policies', 'RentalPolicyController')->only(['index', 'update']);

        ################################### Settings ##########################################
        Route::resource('settings', 'SettingController')->only(['index', 'update']);
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
        return '<h1> migrated</h1>';
    });
    Route::get('/seed', function () {
        Artisan::call('db:seed');
        return '<h1> seeded</h1>';
    });
    Route::fallback(function () {
        return redirect('admin/home');
    });
});//end Prefix
