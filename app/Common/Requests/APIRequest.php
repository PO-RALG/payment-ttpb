<?php

namespace App\Common\Requests;

use App\Common\Support\ResponseUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class APIRequest extends FormRequest
{
    /**
     * Prepare a JSON error body for failed validation.
     */
    public function response(array $errors)
    {
        $messages = implode(' ', Arr::flatten($errors));

        return response()->json(
            ResponseUtil::makeError($messages),
            Response::HTTP_BAD_REQUEST
        );
    }
}
