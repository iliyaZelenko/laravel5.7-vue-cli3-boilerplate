<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($data  = NULL, $message = NULL, $code = 200)
    {
        $response = $data;

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 404) // , $errorMessages = []
    {
        $response = [
            // 'success' => false,
            'message' => $error
        ];

        return response()->json($response, $code);
    }
}
