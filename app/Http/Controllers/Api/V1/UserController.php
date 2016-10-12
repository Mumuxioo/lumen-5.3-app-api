<?php

namespace App\Http\Controllers\Api\V1;

use Api\Transformers\UserTransformer;
use Api\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Http\Request;
use  App\Http\ApiHelper;

class UserController extends BaseController
{
    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @api {put} /user/password 修改密码(edit password)
     * @apiDescription 修改密码(edit password)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} old_password          旧密码
     * @apiParam {String} password              新密码
     * @apiParam {String} password_confirmation 确认新密码
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "password": [
     *             "两次输入的密码不一致",
     *             "新旧密码不能相同"
     *         ],
     *         "password_confirmation": [
     *             "两次输入的密码不一致"
     *         ],
     *         "old_password": [
     *             "密码错误"
     *         ]
     *     }
     */
    public function editPassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ], [
            'old_password.required' => '请输入密码',
            'password.required' => '请输入密码',
            'password_confirmation.required' => '请输入密码',
            'password.confirmed' => '两次密码不一致',
            'password.different' => '旧密码和新密码不能相同',
            'password_confirmation.same' => '两次密码不一致',

        ]);

        if ($validator->fails()) {
            return ApiHelper::toError($validator->messages());
        }

        $user = $this->user();

        $auth = \Auth::once([
            'user_phone' => $user->user_phone,
            'password' => $request->get('old_password'),
        ]);

        if (!$auth) {
            return ApiHelper::toError('旧密码错误', 'error', 401);
        }

        $password = app('hash')->make($request->get('password'));
        $this->userRepository->update($user->user_id, ['password' => $password]);

        return ApiHelper::toJson('修改成功');
    }


    /**
     * @api {get} /user 当前用户信息(current user info)
     * @apiDescription 当前用户信息(current user info)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 2,
     *         "email": 'liyu01989@gmail.com',
     *         "name": "foobar",
     *         "created_at": "2015-09-08 09:13:57",
     *         "updated_at": "2015-09-08 09:13:57",
     *         "deleted_at": null
     *       }
     *     }
     */
    public function getUserInfo()
    {
        return ApiHelper::toSuccess($this->user());
    }

    /**
     * @api {patch} /user 修改个人信息(update my info)
     * @apiDescription 修改个人信息(update my info)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} [user_name] user_name
     * @apiParam {String} [user_nickname] user_nickname
     * @apiParam {String} [user_phone] user_phone
     * @apiParam {String} [user_address] user_address
     * @apiParam {int} [user_profession_type] user_profession_type 职业
     * @apiParam {int} [user_sex] user_sex
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200,
     * "message": "",
     * "data": {
     * "user_id": 7,
     * "user_name": "tony1",
     * "user_nickname": "",
     * "user_sex": "1",
     * "user_phone": "",
     * "user_address": null,
     * "user_profession_type": null,
     * "user_fans_num": null,
     * "user_focus_num": 0,
     * "user_blacklist_num": 0,
     * "user_credits_num": 0,
     * "user_charm_num": 0,
     * "user_charm_ranking": 0,
     * "user_credits_ranking": 0,
     * "last_time": null,
     * "created_at": "2016-09-24 09:54:26",
     * "updated_at": "2016-09-25 18:24:16",
     * "deleted_at": null
     * }
     * }
     */
    public function patch(Request $request)
    {

        $validator = \Validator::make($request->input(), [
            'user_nickname' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return ApiHelper::toError($validator->messages());
        }


        $user = $this->user();
        $only = [
            'user_name',
            'user_nickname',
            'user_sex',
            'user_address',
            'user_profession_type',
        ];

        $attributes = array_filter($request->only($only));

        if ($attributes) {
            $user = $this->userRepository->update($user->user_id, $attributes);
        }

        //return $this->response->item($user, new UserTransformer());
        return ApiHelper::toJson($user);
    }


    /**
     * @api {post} /user/avatar 修改个人头像(upload my avatar)
     * @apiDescription 修改个人头像(upload my avatar)
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     *
     * @apiParam  {file} avatar 头像图片文件
     * @apiParam  {String} token token
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *  "status": "success",
     *  "status_code": 200,
     *  "message": "上传头像成功",
     *  "data": {
     *  "avatar_url": "uploads/avatar/2016-09-267bb5938e78cc8b6cd438be83cc8d78d1.jpg"
     *  }
     * }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 403 Bad Request
     *    {
     *      "status": "error",
     *      "status_code": 403,
     *      "message": {
     *          "上传图片不存在"
     *      }
     *   }
     */
    public function imgUpload(Request $request)
    {

        $file = $request->file('user_avatar');

        if (!$request->hasFile('user_avatar')) {
            return ApiHelper::toError('上传图片不存在');
        }

        if (!$request->file('user_avatar')->isValid()) {
            return ApiHelper::toError('图片上传图片失败');
        }

        $clientName = $file->getClientOriginalName();

        $tmpName = $file->getFileName();

        $realPath = $file->getRealPath();

        $extension = $file->getClientOriginalExtension();

        $mimeTye = $file->getMimeType();

        $newName = md5(date('ymdhis') . $clientName) . "." . $extension;

        $allowed_extensions = ["png", "jpg", "gif"];

        $filePath = "uploads/avatar/" . date('Y-m-d', time());//这里是用户最终裁切好的头像存放目录，当然你可以按年月日目录结构来存放

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        if ($extension && !in_array($extension, $allowed_extensions)) {
            return ApiHelper::toError('只能上传png、 jpg 、 gif类型图片');
        }

        $path = $file->move($filePath, $newName); //这里是缓存文件夹，存放的是用户上传的原图，这里要返回原图地址给flash做裁切用

        $data['user_avatar'] = $filePath . $newName;

        $user = $this->user();

        $user = $this->userRepository->update($user->user_id, $data);

        return ApiHelper::toJson($data, '上传头像成功');

    }


}
