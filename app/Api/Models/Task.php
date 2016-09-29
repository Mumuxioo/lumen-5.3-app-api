<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends BaseModel
{
    use SoftDeletes;

    protected $table = 'task_info';

    protected $primaryKey = 'task_id';

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo('Api\Models\User');
    }

//    public function comments()
//    {
//        return $this->hasMany('Api\Models\PostComment');
//    }
}
