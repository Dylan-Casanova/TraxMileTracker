<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class TripsRequest extends FormRequest
{

    public function rules()
    {
        return [
            'car_id' => 'required|numeric',
            'miles' => 'required|numeric',
            'date' => 'required|date',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('date')) {
                $date = $this->get('date');
                $parsedDate = date('Y-m-d H:i:s', strtotime($date));

                if (!$parsedDate) {
                    $validator->errors()->add('date', 'Invalid date format.');
                }
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422));
    }
}
