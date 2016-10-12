<?php

namespace App\Http\Controllers\Api\V1;

use Api\Models\PhoneCode;
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
     *   {
            "status": "success",
            "status_code": 200,
            "message": "登陆成功",
                "data": {
                    "user": {
                    "user_id": 1,
                    "user_name": "",
                    "user_nickname": "15201255173",
                    "user_sex": null,
                    "user_phone": "15201255173",
                    "user_address": null,
                    "user_avatar": null,
                    "user_profession_type": null,
                    "user_fans_num": null,
                    "user_focus_num": 0,
                    "user_blacklist_num": 0,
                    "user_credits_num": 0,
                    "user_charm_num": 0,
                    "user_charm_ranking": 0,
                    "user_credits_ranking": 0,
                    "last_time": null,
                    "id_cards": null,
                    "education": 0,
                    "car": 0,
                    "real_name": 0,
                    "created_at": "2016-09-28 21:53:51",
                    "updated_at": "2016-09-28 21:53:51",
                    "deleted_at": null
                },
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWwuYm91bnR5LmNvbS9hcGkvdXNlci9sb2dpbiIsImlhdCI6MTQ3NjI4NTcwMiwiZXhwIjoxNDc2Mjg5MzAyLCJuYmYiOjE0NzYyODU3MDIsImp0aSI6IjNlY2M0OTY4NjI3MjYyMDA1MTU1OGQ2ZjIzN2E2MzBhIiwic3ViIjoxfQ.Upr5bMUJeth0OMbPnPAuOoD1mgzWZ-XdQWYDM08GFCY"
            }
        }
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
        $phone = $request->get('user_phone');
        $user = $this->userRepository->where(['user_phone'=>$phone])->first();
        return ApiHelper::toJson(compact('user','token'),'登陆成功');
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
     * @api {post} /user/sendSMS 发送手机验证码(sendSMS)
     * @apiDescription 手机验证码(sendSMS)
     * @apiGroup Auth
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {user_phone}  user_phone   user_phone
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": "success",
     *          "status_code": 200,
     *          "message": "发送成功",
     *          "data": "316954"
     *      }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 403 Bad Request
     *    {
     *      "status": "error",
     *      "status_code": 403,
     *      "message": {
     *          "user_phone": [
     *              "手机号不能为空"
     *          ]
     *      },
     *      "data": ""
     *   }
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
            return ApiHelper::toError("短信验证码发送失败");
        }
        if($result->statusCode != 0) {
            return ApiHelper::toError($result->statusMsg);
        }else{

            $tempPhone = PhoneCode::where('phone', $phone)->first();

            if($tempPhone == null) {
                $tempPhone = new PhoneCode;
            }

            $tempPhone->phone = $phone;
            $tempPhone->code = $code;
            $tempPhone->deadline = date('Y-m-d H-i-s', time() + 5*60);
            $tempPhone->save();

            return ApiHelper::toJson($code,"发送成功");
        }


    }



    /**
     * @api {post} /user/register 注册(register)
     * @apiDescription 注册(register)
     * @apiGroup Auth
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {Phone}  phone   phone[unique]
     * @apiParam {String} password   password
     * @apiParam {String} phone_code   phone_code 验证码
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": "success",
     *          "status_code": 200,
     *          "message": "注册成功",
     *          "data": {
     *              "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY"
     *          }
     *      }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 403 Bad Request
     *    {
     *      "status": "error",
     *      "status_code": 403,
     *      "message": {
     *          "user_phone": [
     *          "手机号已注册"
     *          ]
     *      },
     *      "data": ""
     *   }
     */
    public function register(Request $request)
    {


        $rules = [
            'user_phone' => 'required|unique:users|regex:/^1[34578][0-9]{9}$/',
            'password' => 'required',
            'phone_code' => 'required|min:6',
        ];

        $message = [
            'user_phone.required' => '手机号不能为空',
            'user_phone.unique' => '手机号已注册',
            'user_phone.regex' => '手机号格式不正确',
            'password.required' => '请填写登陆密码',
            'phone_code.required' => '请填写验证码',
            'phone_code.min' => '手机验证码为6位',
        ];

        $validator = Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            return ApiHelper::toError($validator->messages());
        }

        $phone = $request->get('user_phone');
        $password = $request->get('password');
        $phone_code = $request->get('phone_code');

        $tempPhone = PhoneCode::where('phone', $phone)->first();

        if($tempPhone == null){
            return ApiHelper::toError('请发送验证码');
        }
        if($tempPhone->code == $phone_code) {

            if(time() > strtotime($tempPhone->deadline)) {
                return ApiHelper::toError('手机验证码不正确');
            }

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

        return ApiHelper::toError('手机验证码不正确');


    }
}
