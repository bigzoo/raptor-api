<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function return404($model = null, $data = null, $message = null, $code = 404)
    {
        $model = $model ? $model : "resource";
        return response()->json([
            'code' => $code,
            'data' => $data ? $data : null,
            'message' => $message ? $message : 'The requested '. $model .' could not be found'
        ], $code);
    }

    public function responseJSON($resource, $message = 'ok', $code = 200)
    {
        return response()->json([
            'code' => $code,
            'data' => $resource->toArray(),
            'message' => $message
        ], $code);
    }
}
