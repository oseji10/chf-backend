<?php

namespace App\Helpers;


/*  A UTILITY HELPER TO WRAP ALL API RESPONSE AND RETURN THEM IN A 
*   UNIIFORM FORMAT
 */

class ResponseHelper
{

    /* 
    *   
     */
    public static function ajaxResponseBuilder($success = true, $message = "success", $data = null, $status_code = 200)
    {
        return response([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }

    /* 
    *
     */
    public static function noDataErrorResponse($message = '', $status_code = 500)
    {
        return self::ajaxResponseBuilder(false, $message, null, $status_code);
    }

    /* 
    *
     */
    public static function noDataSuccessResponse($message = '', $status_code = 200)
    {
        return self::ajaxResponseBuilder(true, $message, null, $status_code);
    }

    public static function exceptionHandler($ex)
    {
        return self::noDataErrorResponse($ex->getMessage() ?? __('errors.server'), $ex->getCode() > 0 ? $ex->getCode() : 500);
    }
}
