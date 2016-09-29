<?php

namespace App\Http\Controllers\Api\V1;

use Api\Transformers\TaskTransformer;
use Api\Repositories\Contracts\TaskRepositoryContract;
use App\Http\ApiHelper;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    private $taskRepository;

    public function __construct(TaskRepositoryContract $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @api {get} /tasks 任务列表(task list)
     * @apiDescription 任务列表(task list)  - 最新任务 热门推荐 猜我喜欢
     * @apiGroup task
     * @apiPermission none
     * @apiParam {String='comments:limit(x)','user'} [include]  include
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *     "data": [
     *       {
     *         "id": 1,
     *         "user_id": 3,
     *         "title": "foo",
     *         "content": "",
     *         "created_at": "2016-03-30 15:36:30",
     *         "user": {
     *           "data": {
     *             "id": 3,
     *             "email": "foo@bar.com1",
     *             "name": "",
     *             "avatar": "",
     *             "created_at": "2016-03-30 15:34:01",
     *             "updated_at": "2016-03-30 15:34:01",
     *             "deleted_at": null
     *           }
     *         },
     *         "comments": {
     *           "data": [],
     *           "meta": {
     *             "total": 0
     *           }
     *         }
     *       }
     *     ],
     *     "meta": {
     *       "pagination": {
     *         "total": 2,
     *         "count": 2,
     *         "per_page": 15,
     *         "current_page": 1,
     *         "total_pages": 1,
     *         "links": []
     *       }
     *     }
     *   }
     */
    public function index()
    {
        $tasks = $this->taskRepository->paginate();

        return $this->response->paginator($tasks, new TaskTransformer());
    }

    /**
     * @api {get} /user/tasks 我的任务列表(my task list)
     * @apiDescription 我的任务列表(my task list)
     * @apiGroup task
     * @apiPermission none
     * @apiParam {String='comments:limit(x)'} [include]  include
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *     "data": [
     *       {
     *         "id": 1,
     *         "user_id": 3,
     *         "title": "foo",
     *         "content": "",
     *         "created_at": "2016-03-30 15:36:30",
     *         "user": {
     *           "data": {
     *             "id": 3,
     *             "email": "foo@bar.com1",
     *             "name": "",
     *             "avatar": "",
     *             "created_at": "2016-03-30 15:34:01",
     *             "updated_at": "2016-03-30 15:34:01",
     *             "deleted_at": null
     *           }
     *         },
     *         "comments": {
     *           "data": [],
     *           "meta": {
     *             "total": 0
     *           }
     *         }
     *       }
     *     ],
     *     "meta": {
     *       "pagination": {
     *         "total": 2,
     *         "count": 2,
     *         "per_page": 15,
     *         "current_page": 1,
     *         "total_pages": 1,
     *         "links": []
     *       }
     *     }
     *   }
     */
    public function myTask()
    {
        $tasks = $this->taskRepository
            ->where(['user_id' => $this->user()->user_id])
            ->paginate();

        return $this->response->paginator($tasks, new TaskTransformer());
    }

    /**
     * @api {get} /tasks/{id} 任务详情(task detail)
     * @apiDescription 任务详情(task detail)
     * @apiGroup task
     * @apiPermission none
     * @apiParam {String='comments','user'} [include]  include
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *     "data": {
     *       "id": 1,
     *       "user_id": 3,
     *       "title": "foo",
     *       "content": "",
     *       "created_at": "2016-03-30 15:36:30",
     *       "user": {
     *         "data": {
     *           "id": 3,
     *           "email": "foo@bar.com1",
     *           "name": "",
     *           "avatar": "",
     *           "created_at": "2016-03-30 15:34:01",
     *           "updated_at": "2016-03-30 15:34:01",
     *           "deleted_at": null
     *         }
     *       },
     *       "comments": {
     *         "data": [
     *           {
     *             "id": 1,
     *             "task_id": 1,
     *             "user_id": 1,
     *             "reply_user_id": 0,
     *             "content": "foobar",
     *             "created_at": "2016-04-06 14:51:34"
     *           }
     *         ],
     *         "meta": {
     *           "total": 1
     *         }
     *       }
     *     }
     *   }
     */
    public function detail($id)
    {
        $task = $this->taskRepository->find($id);

        if (! $task) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($task, new TaskTransformer());
    }

    /**
     * @api {post} /tasks 发布任务(create task)
     * @apiDescription 发布任务(create task)
     * @apiGroup task
     * @apiPermission jwt
     * @apiParam {String} title  task title
     * @apiParam {String} content  task content
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'task_title' => 'required|string|max:50',
            'task_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return  ApiHelper::toError($validator->messages());
        }

        $attributes = $request->only('task_title', 'task_content','task_type','completion_time','bounty_price','longitude','latitude');
        $attributes['user_id'] = $this->user()->user_id;
        $task = $this->taskRepository->create($attributes);

       return ApiHelper::toJson(['taskId'=>$task->task_id],'发布成功');
    }

    /**
     * @api {patch} /tasks/{id} 修改任务(update task)
     * @apiDescription 修改任务(update task)
     * @apiGroup task
     * @apiPermission jwt
     * @apiParam {String} title  task title
     * @apiParam {String} content  task content
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 204 NO CONTENT
     */
    public function update($id, Request $request)
    {
        $task = $this->taskRepository->find($id);

        if (! $task) {
            return $this->response->errorNotFound();
        }

        // 不属于我的forbidden
        if ($task->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = \Validator::make($request->input(), [
            'title' => 'required|string|max:50',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $this->taskRepository->update($id, $request->only('title', 'content'));

        return $this->response->noContent();
    }

    /**
     * @api {delete} /tasks/{id} 删除任务(delete task)
     * @apiDescription 删除任务(delete task)
     * @apiGroup task
     * @apiPermission jwt
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 204 NO CONTENT
     */
    public function destroy($id)
    {
        $task = $this->taskRepository->find($id);

        if (! $task) {
            return $this->response->errorNotFound();
        }

        // 不属于我的forbidden
        if ($task->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $this->taskRepository->destroy($id);

        return $this->response->noContent();
    }
}
