<?php

namespace Api\Repositories\Eloquent;

use Api\Repositories\Contracts\PostRepositoryContract;

class PostRepository extends BaseRepository implements PostRepositoryContract
{
    public function model()
    {
        return 'Api\Models\Post';
    }
}
