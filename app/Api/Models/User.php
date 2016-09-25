<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseModel implements AuthenticatableContract, JWTSubject
{
    // 软删除和用户验证attempt
    use SoftDeletes, Authenticatable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    // 查询用户的时候，不暴露密码
    protected $hidden = ['password','is_online','token'];


    public function posts()
    {
        return $this->hasMany('Api\Models\Post');
    }

    public function postComments()
    {
        return $this->hasMany('Api\Models\PostComment');
    }

    // jwt 需要实现的方法
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // jwt 需要实现的方法
    public function getJWTCustomClaims()
    {
        return [];
    }
}
