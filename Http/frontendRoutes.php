<?php

use Illuminate\Routing\Router;

/** @var Router $router */
Route::prefix('lugares')->group(function (Router $router) {
    $router->get('/', [
        'as' => 'iplaces.place.index',
        'uses' => 'OldPublicController@index',
    ]);
    $router->get('/{slugcategory}', [
        'as' => 'iplaces.place.category',
        'uses' => 'OldPublicController@category',
    ]);
    $router->get('{slugcategory}/{slugplace}', [
        'as' => 'iplaces.place.show',
        'uses' => 'OldPublicController@show',
    ]);
});

Route::prefix(LaravelLocalization::setLocale())->middleware('localize')->group(function (Router $router) {
    $locale = LaravelLocalization::setLocale() ?: App::getLocale();

    $router->get(trans('iplaces::routes.places.index.index'), [
        'as' => $locale.'.iplaces.place.index',
        'uses' => 'PublicController@index',
    ]);
    $router->get(trans('iplaces::routes.places.index.category'), [
        'as' => $locale.'.iplaces.place.category',
        'uses' => 'PublicController@index',
    ]);
    $router->get(trans('iplaces::routes.places.index.place'), [
        'as' => $locale.'.iplaces.place.show',
        'uses' => 'PublicController@show',
    ]);
});
