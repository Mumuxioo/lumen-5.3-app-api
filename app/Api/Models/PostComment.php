<?php

namespace Api\Models;

class PostComment extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('Api\Models\User');
    }

    public function post()
    {
        return $this->belongsTo('Api\Models\Post');
    }
}
