<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class CustomResponse implements Responsable
{

    protected int $httpCode;
    protected mixed $data;
    protected string $errorMessage;

    public function __construct(int $httpCode, mixed $data = [], string $errorMessage = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }

    public function toResponse($request)
    {

        $payLoad = match (true) {
            $this->httpCode >= 500 => ['error_message' => 'Server error'],
            $this->httpCode >= 400 => ['error_message' => $this->errorMessage],
            $this->httpCode >= 200 => ['data' => $this->data],
        };

        return response()->json(
            data: $payLoad,
            status: $this->httpCode,
            ///TODO : Add option : JSON_UNESCAPED_UNICODE
        );
    }


    public static function ok(mixed $data)
    {
        return new static(200, data: $data);
    }

    public static function created(mixed $data)
    {
        return new static(201, data: $data);
    }

    public static function deleted()
    {
        return new static(204);
    }

    public static function badRequest(string $errorMessage = "Invalid data !!")
    {
        return new static(400, errorMessage: $errorMessage);
    }

    public static function unauthorized(string $errorMessage = "Pleas login to do this request")
    {
        return new static(401, errorMessage: $errorMessage);
    }

    public static function forbidden(string $errorMessage = "You are not allowed to do this request")
    {
        return new static(403, errorMessage: $errorMessage);
    }

    public static function notFound(string $errorMessage = "Not found")
    {
        return new static(404, errorMessage: $errorMessage);
    }

    public static function serverError()
    {
        return new static(500);
    }
}
