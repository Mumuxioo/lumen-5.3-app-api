<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use SoftDeletes;

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo('Api\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('Api\Models\PostComment');
    }
}
