<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class JsonResponse extends Response
{
    public static function success($data)
    {
        $status = 'success';
        return Response::json(compact('status', 'data'));
    }

    public static function fail($data)
    {
        $status = 'fail';
        return Response::json(compact('status', 'data'));
    }

    public static function error($message, $code = null, $data = null)
    {
        $status = 'error';
        return Response::json(compact('status', 'message', 'code', 'data'));
    }
}
