<?php
namespace App\Helpers;

Trait ApiResponse {

    protected static $response = [
        'status' => 'success',
        'message' => 'OK',
        'data' => null,
    ];

    static function success($data = null, $message = 'OK')
    {
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, 200);
    }

    static function error($data = null, $message = 'System error', $code = 400)
    {
        self::$response['status'] = 'error';
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, $code);
    }
}