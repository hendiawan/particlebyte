<?php
namespace App\Helpers;

class ApiFormatter
{
    protected static $response=[
        'code'=>null,
        'message'=>null,
        'data'=>null,
    ];

    public function createApi($code=null, $message=null, $data=null){
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response,self::$response['code']);
    }
    public function sayHello(){
        return response()->json('hallo');
    }
}


