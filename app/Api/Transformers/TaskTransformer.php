<?php

namespace Api\Transformers;

use Api\Models\Task;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'comments'];

    public function transform(Task $task)
    {
        return $task->attributesToArray();
    }

    public function includeUser(Task $task)
    {
        return $this->item($task->user, new UserTransformer());
    }

    public function includeComments(Task $task, ParamBag $params = null)
    {
        $limit = 10;
        if ($params) {
            $limit = (array) $params->get('limit');
            $limit = (int) current($limit);
        }

        $comments = $task->comments()->limit($limit)->get();
        $total = $task->comments()->count();

        return $this->collection($comments, new PostCommentTransformer())->setMeta(['total' => $total]);
    }
}
