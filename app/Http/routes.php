<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});


$api = app('Dingo\Api\Routing\Router');

// v1 version API
// choose version add this in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {

    // Auth
    // login
    $api->post('user/login', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login',
    ]);

    // sendSMS
    $api->post('user/sendSMS', [
        'as' => 'users.sendSMS',
        'uses' => 'AuthController@sendSMS',
    ]);

    // register
    $api->post('user/register', [
        'as' => 'users.register',
        'uses' => 'AuthController@register',
    ]);

    // AUTH
    // refresh jwt token
    $api->post('token/new', [
        'as' => 'auth.token.new',
        'uses' => 'AuthController@refreshToken',
    ]);


    // need authentication
    $api->group(['middleware' => 'jwt.auth'], function ($api) {

        // update my password
        $api->put('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        // USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@getUserInfo',
        ]);

        // update part of me
        $api->patch('user', [
            'as' => 'user.update',
            'uses' => 'UserController@patch',
        ]);

        // update avatar of me 头像
        $api->post('user/avatar', [
            'as' => 'user.avatar',
            'uses' => 'UserController@imgUpload',
        ]);


        //TASK COMMENT
        // task index
        $api->get('tasks', [
            'as' => 'tasks.index',
            'uses' => 'TaskController@index',
        ]);

        // user's task
        $api->get('user/tasks', [
            'as' => 'tasks.myTask',
            'uses' => 'TaskController@myTask',
        ]);

        // user's task
        $api->get('task/detail/{id}', [
            'as' => 'tasks.detail',
            'uses' => 'TaskController@detail',
        ]);

        // create a task
        $api->post('tasks', [
            'as' => 'tasks.store',
            'uses' => 'TaskController@store',
        ]);

        // update part of a post
        $api->patch('tasks/{id}', [
            'as' => 'tasks.update',
            'uses' => 'TaskController@update',
        ]);
        // delete a post
        $api->delete('tasks/{id}', [
            'as' => 'tasks.destroy',
            'uses' => 'TaskController@destroy',
        ]);

        // POST COMMENT
        // create a comment
        $api->post('posts/{postId}/comments', [
            'as' => 'posts.comments.store',
            'uses' => 'PostCommentController@store',
        ]);
        $api->put('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.update',
            'uses' => 'PostCommentController@update',
        ]);
        // delete a comment
        $api->delete('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.destroy',
            'uses' => 'PostCommentController@destroy',
        ]);
    });
});