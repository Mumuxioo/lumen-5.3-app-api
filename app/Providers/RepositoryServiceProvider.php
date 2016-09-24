<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Api\Repositories\Contracts\UserRepositoryContract::class,
            \Api\Repositories\Eloquent\UserRepository::class
        );
        $this->app->bind(
            \Api\Repositories\Contracts\PostRepositoryContract::class,
            \Api\Repositories\Eloquent\PostRepository::class
        );
        $this->app->bind(
            \Api\Repositories\Contracts\PostCommentRepositoryContract::class,
            \Api\Repositories\Eloquent\PostCommentRepository::class
        );
    }
}
