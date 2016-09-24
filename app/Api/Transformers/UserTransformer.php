<?php

namespace Api\Transformers;

use Api\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return $user->attributesToArray();
    }
}
