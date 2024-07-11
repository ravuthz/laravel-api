<?php

if (!function_exists('json_ok')) {
    function json_ok($data, $status = 200, $message = 'OK')
    {
        $res = [
            'success' => true,
            'message' => $message,
        ];

        if (isset($data)) {
            $res['data'] = $data;
        }

        return response()->json($res, $status);
    }
}

if (!function_exists('json_error')) {
    function json_error($error, $status = 500, $message = 'Internal Server Error')
    {
        $res = [
            'success' => false,
            'message' => $error->getMessage() ?? $message,
        ];

        if (isset($data)) {
            $res['error'] = $error;
        }

        return response()->json($res, $status);
    }
}