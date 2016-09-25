<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 2016/9/24
 * Time: 17:11
 */

namespace Api\Models;

class PhoneCode extends BaseModel{

    protected $table = 'codes';
    protected $primaryKey = 'id';

    public $timestamps = false;
}