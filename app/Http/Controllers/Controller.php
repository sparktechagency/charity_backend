<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendResponse($data, $message = "", $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    public function sendError($errorMessage, $errors = [], $status = 404)
    {
        return response()->json([
            'success' => false,
            'message' => $errorMessage,
            'errors' => $errors
        ], $status);
    }
}
