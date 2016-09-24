<?php

namespace App\Http\Controllers\Api\V1;

use Api\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Validator;
use JWTAuth;
use App\Http\ApiHelper;
use App\Tools\SMS\SendTemplateSMS;

class AuthController extends BaseController
{

    protected $userRepository;

    protected $auth;

    public function __construct(UserRepositoryContract $userRepository, AuthManager $auth)
    {
        $this->userRepository = $userRepository;

        $this->auth = $auth;
    }

    /**
     * @api {post} /authorization 登录(login)
     * @apiDescription 登录(login)
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {Phone} phone     手机号
     * @apiParam {String} password  密码
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *    {
     *      "status": "success",
     *      "code": 200,
     *      "message": "登陆成功",
     *      "data": {
     *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWwubHVtZW41LjMuY29tL2FwaS9hdXRob3JpemF0aW9uIiwiaWF0IjoxNDc0MDkwNTAwLCJleHAiOjE0NzQwOTQxMDAsIm5iZiI6MTQ3NDA5MDUwMCwianRpIjoiZTNmNzU0NjcwZjY3YmJmMjFiNjNkOTEwMTM5ZWE3YTQiLCJzdWIiOjZ9.gXZlIG7JOi2xX11fawLV6ID4WpTOdp5VKvwPfLFsHXU"
     *           }
     *     }
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "status": "error",
     *         "code": 403,
     *         "message": {
     *         "user_phone": [
     *               "手机号格式不正确"
     *               ]
     *           }
     *       }
     */
    public function login(Request $request)
    {
        $rules = [
            'user_phone' => 'required|regex:/^1[34578][0-9]{9}$/',
            'password' => 'required',
        ];

        $message = [
            'user_phone.required' => '手机号不能为空',
            'user_phone.regex' => '手机号格式不正确',
            'password.required' => '请填写登陆密码',
        ];

        $validator = Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            return ApiHelper::toError($validator->messages());
        }

        $credentials = $request->only('user_phone', 'password');

        try {
            // 验证失败返回403
            if (! $token = $this->auth->attempt($credentials)) {
                return ApiHelper::toError('用户名或密码错误！');
            }
        }catch (JWTException $e) {
            return ApiHelper::toError('token创建失败');
        }

        return ApiHelper::toJson(compact('token'),'登陆成功');
    }

    /**
     * @api {post} /auth/token/new 刷新token(refresh token)
     * @apiDescription 刷新token(refresh token)
     * @apiGroup Auth
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization 用户旧的jwt-token, value已Bearer开头
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *    {
     *       "status": "success",
     *       "status_code": 200,
     *       "message": "操作成功",
     *       "data": {
     *           "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWwubHVtZW41LjMuY29tL2FwaS9hdXRoL3Rva2VuL25ldyIsImlhdCI6MTQ3NDA5NzE2NCwiZXhwIjoxNDc0MTAwNzc1LCJuYmYiOjE0NzQwOTcxNzUsImp0aSI6ImNhODliMTUxNWNkZmNmN2ExY2I0MjZkMGE2MDUzMjllIiwic3ViIjo2fQ.EfPEd6tE9Ui9vfj7dcs9twsv8INW2THhnsqGPO-xXQQ"
     *       }
     *     }
     */
    public function refreshToken()
    {
        $token = $this->auth->refresh();

        return ApiHelper::toJson(compact('token'),'操作成功');
    }


    /**
     * @api {post} /register 注册(register)
     * @apiDescription 注册(register)
     * @apiGroup Auth
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {Phone}  phone   phone[unique]
     * @apiParam {String} password   password
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 403 Bad Request
     *     {
     *         "phone": [
     *             "该手机号码已注册"
     *         ],
     *     }
     */
    public function sendSMS(Request $request)
    {

        $rules = [
            'user_phone' => 'required|regex:/^1[34578][0-9]{9}$/',
        ];

        $message = [
            'user_phone.required' => '手机号不能为空',
            'user_phone.regex' => '手机号格式不正确',
        ];

        $validator = Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            return ApiHelper::toError($validator->messages());
        }
        $phone = $request->get('user_phone');

        $sendTemplateSMS = new SendTemplateSMS;
        $code = '';
        $charset = '1234567890';
        $_len = strlen($charset) - 1;
        for ($i = 0;$i < 6;++$i) {
            $code .= $charset[mt_rand(0, $_len)];
        }

        $result = $sendTemplateSMS->sendTemplateSMS($phone, array($code, 5), 1);
        if($result == NULL ) {
            return ApiHelper::toError($result,"result error");
        }
        if($result->statusCode != 0) {
            return ApiHelper::toError($result,"code error");
        }else{
            return ApiHelper::toJson($code,"发送成功");
        }


    }



    /**
     * @api {post} /register 注册(register)
     * @apiDescription 注册(register)
     * @apiGroup Auth
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {Phone}  phone   phone[unique]
     * @apiParam {String} password   password
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 403 Bad Request
     *     {
     *         "phone": [
     *             "该手机号码已注册"
     *         ],
     *     }
     */
    public function register(Request $request)
    {


        $rules = [
            'user_phone' => 'required|unique:users|regex:/^1[34578][0-9]{9}$/',
            'password' => 'required',
        ];

        $message = [
            'user_phone.required' => '手机号不能为空',
            'user_phone.unique' => '手机号已注册',
            'user_phone.regex' => '手机号格式不正确',
            'password.required' => '请填写登陆密码',
        ];

        $validator = Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            return ApiHelper::toError($validator->messages());
        }

        $phone = $request->get('user_phone');
        $password = $request->get('password');

        $attributes = [
            'user_phone' => $phone,
            'user_nickname' => $phone,
            'password' => app('hash')->make($password),
        ];

        $user = $this->userRepository->create($attributes);

        // 用户注册事件
        $token = $this->auth->fromUser($user);

        return ApiHelper::toJson(compact('token'),'注册成功');
    }
}
