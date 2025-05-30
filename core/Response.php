<?php
namespace Core;

class Response {

    /** 200 OK */
    public static function response200(string $message = null, $data = ''): array {
        error_log("Success: " . print_r($data, true));
        return [
            'statusCode' => 200,
            'message'    => $message,
            'data'       => $data
        ];
    }

    /** 201 Created */
    public static function response201(string $message = null, $data = ''): array {
        error_log("Created: " . print_r($data, true));
        return [
            'statusCode' => 201,
            'message'    => $message,
            'data'       => $data
        ];
    }

    /** 204 No Content */
    public static function response204(string $message = null): array {
        error_log("No Content");
        return [
            'statusCode' => 204,
            'message'    => $message,
            'data'       => null
        ];
    }

    /** 400 Bad Request */
    public static function response400(string $message = null, $data = null, string $error = ''): array {
        error_log("Bad Request: $error");
        return [
            'statusCode' => 400,
            'message'    => $message,
            'data'       => $data
        ];
    }

    /** 401 Unauthorized */
    public static function response401(string $message = null, $data = null, string $error = ''): array {
        error_log("Unauthorized: $error");
        return [
            'statusCode' => 401,
            'message'    => $message,
            'data'       => $data
        ];
    }

    /** 403 Forbidden */
    public static function response403(string $message = null, $data = null, string $error = ''): array {
        error_log("Forbidden: $error");
        return [
            'statusCode' => 403,
            'message'    => $message,
            'data'       => $data
        ];
    }

    /** 404 Not Found */
    public static function response404(string $message = null, $data = null, string $error = ''): array {
        error_log("Not Found: $error");
        return [
            'statusCode' => 404,
            'message'    => $message,
            'data'       => $data
        ];
    }

    /** 500 Internal Server Error */
    public static function response500(string $message = null, $data = null, string $error = ''): array {
        error_log("Server Error: $error");
        return [
            'statusCode' => 500,
            'message'    => $message,
            'data'       => $data
        ];
    }
}
