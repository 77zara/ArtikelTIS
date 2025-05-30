<?php
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Grup rute untuk API artikel
$router->group(['prefix' => 'api'], function () use ($router) {
    // GET /api/articles - Menampilkan semua artikel
    $router->get('articles', 'ArticleController@index');

    // POST /api/articles - Membuat artikel baru
    $router->post('articles', 'ArticleController@store');

    // GET /api/articles/{id} - Menampilkan detail artikel
    $router->get('articles/{id}', 'ArticleController@show');

    // PUT /api/articles/{id} - Mengupdate artikel
    $router->put('articles/{id}', 'ArticleController@update');

    // DELETE /api/articles/{id} - Menghapus artikel
    $router->delete('articles/{id}', 'ArticleController@destroy');
});