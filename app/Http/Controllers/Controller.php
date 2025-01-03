<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

     /**
     * success response method.
     */
    public function sendSuccessResponse($result, $message, $code){
        $response = [
            'status' => $code,
            'data' => $result,
            'message' => $message
        ];

        return new JsonResponse($response, $code);
    }

    /**
     * error response method.
     */

     public function sendErrorResponse($error, $errorMessages = [], $code)
     {
        $response = [
            'status' => $code,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['error-message'] = $errorMessages;
        }

        return new JsonResponse($response, $code);
     }
}
