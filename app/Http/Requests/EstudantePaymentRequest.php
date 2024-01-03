<?php

namespace App\Http\Requests;

 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstudantePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can adjust the authorization logic if needed
    }

    public function rules()
    {
        return [
            'metodoId' => 'required|integer',
            'contaBancaria' => [
                Rule::requiredIf(function () {
                    return $this->input('metodoId') != 1;
                }),
                'nullable',
            ],
            'total' => 'required|numeric',
            'value' => 'required|numeric|same:total',
            'esquecerMulta' => 'sometimes|boolean',
        ];
    }
}
