<?php

// User Authentication Routes

use App\Http\Controllers\API\User\LocationController;

require base_path('routes/Api/user_auth.php');

// User Relation Routes
require base_path('routes/Api/user_relation.php');

// Profile Routes
require  base_path('routes/Api/profile.php');


//Location Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('location/regions', [LocationController::class, 'getRegions']);

    Route::get('location/regions/{regionId}/subregions', [LocationController::class, 'getSubregions']);

    Route::get('location/subregions/{subregionId}/countries', [LocationController::class, 'getCountries']);

    Route::get('location/countries/{countryId}/states', [LocationController::class, 'getStates']);

    Route::get('location/states/{stateId}/cities', [LocationController::class, 'getCities']);

    Route::get('location/countries/{countryId}/cities', [LocationController::class, 'getCitiesByCountry']);
});
