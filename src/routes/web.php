<?php

/*
|--------------------------------------------------------------------------
| Laravel Logger Web Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'visitoractivity', 'namespace' => 'Baas\LaravelVisitorLogger\App\Http\Controllers', 'middleware' => ['web', 'auth', 'visitoractivity']], function () {

    // Dashboards
    Route::get('/', 'LaravelVisitorLoggerController@showAccessLog')->name('activity');
    Route::get('/cleared', ['uses' => 'LaravelVisitorLoggerController@showClearedActivityLog'])->name('cleared');

    // Drill Downs
    Route::get('/log/{id}', 'LaravelVisitorLoggerController@showAccessLogEntry');
    Route::get('/cleared/log/{id}', 'LaravelVisitorLoggerController@showClearedAccessLogEntry');

    // Forms
    Route::delete('/clear-activity', ['uses' => 'LaravelVisitorLoggerController@clearActivityLog'])->name('clear-activity');
    Route::delete('/destroy-activity', ['uses' => 'LaravelVisitorLoggerController@destroyActivityLog'])->name('destroy-activity');
    Route::post('/restore-log', ['uses' => 'LaravelVisitorLoggerController@restoreClearedActivityLog'])->name('restore-activity');
});
