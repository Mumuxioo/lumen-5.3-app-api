<?php

namespace App\Http;

class ApiHelper
{
    /**
     * 成功返回json对象
     * @param $data 响应数据
     * @param string $message 响应提示信息
     * @param string $status 响应状态
     * @param int $code 响应状态码
     * @return \Illuminate\Http\JsonResponse josn数据
     */
    static public function toJson($data, $message = '', $status = 'success', $code = 200)
    {
        return response()->json([
            'status' => $status,
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * 错误返回json对象
     * @param string $message 响应提示信息
     * @param string $status 响应状态
     * @param int $code 响应状态码
     * @return \Illuminate\Http\JsonResponse josn数据
     */
    static public function toError($data, $message = '', $status = 'error', $code = 403)
    {
        return response()->json([
            'status' => $status,
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * 元数据数组
     * @param $data 响应数据
     * @param string $message
     * @param string $status
     * @param int $code
     * @return array
     */
    static public function toArray($data, $message = '', $status = 'success', $code = 200)
    {
        return [
            'status' => $status,
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
        ];
    }
}