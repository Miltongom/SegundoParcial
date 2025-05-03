<?php

class ResponseHelper
{
    public static function success($data, $method = "get")
    {
        return json_encode([
            'status' => 200,
            'total' => count($data),
            'results' => $data,
            'method' => $method
        ], JSON_UNESCAPED_UNICODE);
    }

    public static function notFound($method = "get")
    {
        return json_encode([
            'status' => 404,
            'results' => 'Not Found',
            'method' => $method
        ], JSON_UNESCAPED_UNICODE);
    }
}
