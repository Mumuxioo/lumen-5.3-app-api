<?php

namespace Api\Repositories\Eloquent;

use Api\Repositories\Contracts\TaskRepositoryContract;

class TaskRepository extends BaseRepository implements TaskRepositoryContract
{
    public function model()
    {
        return 'Api\Models\Task';
    }
}
