<?php

namespace Api\Repositories\Eloquent;

use Api\Repositories\Contracts\UserRepositoryContract;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function model()
    {
        return 'Api\Models\User';
    }
}
