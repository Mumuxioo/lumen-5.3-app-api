<?php

namespace Api\Repositories\Eloquent;

use Api\Repositories\Contracts\PostCommentRepositoryContract;

class PostCommentRepository extends BaseRepository implements PostCommentRepositoryContract
{
    public function model()
    {
        return 'Api\Models\PostComment';
    }
}
