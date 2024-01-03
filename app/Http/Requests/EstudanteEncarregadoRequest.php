<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class EstudanteEncarregadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'estudante.cursoId' => 'required|integer|exists:mensalidade,Curso_id',
            'estudante.classeId' => 'required|integer|exists:mensalidade,Classe_id',
            'estudante.peridoId' => 'required|integer|exists:mensalidade,Periodo_id',
            
            'estudante.primeiroNome' => 'required|string|max:30',
            'estudante.ultimoNome' => 'required|string|max:30', // Maximum 25 characters
            'estudante.nomePai' => 'required|string|max:40',
            'estudante.nomeMae' => 'required|string|max:40',
            'estudante.generoId' => 'required|integer',
            'estudante.dataofbirth' => 'required|date',
            'estudante.numeroDoDocumento' => 'required|string|max:40',
            'estudante.tipoDeDocumento' => 'required|string|max:25',
            'estudante.pais' => 'required|string|max:25',
            'estudante.provincia' => 'required|string|max:25',
            'estudante.municipio' => 'required|string|max:25',
            'estudante.email' => 'sometimes|email|unique:users,email',           
            //  'estudante.telefone' => [
            //     'required',
            //     'regex:/^9\d{8}$/',
            //     'unique:users,numeroDotelefone',

            // ],
            // 'encarregado.telefoneEncarregado' => [
            //     'required',
            //     'regex:/^9\d{8}$/',
            //     'unique:users,numeroDotelefone',
                // function ($attribute, $value, $fail) {
                //     // Get the value of estudante.telefone
                //     $estudanteTelefone = $this->input('estudante.telefone');

                //     // Check if encarregado.telefoneEncarregado is equal to estudante.telefone
                //     if ($value !== $estudanteTelefone) {
                //         $fail('O Numero de Telemovel do encarregado Não pode ser o mesmo Para o Estudante');
                //     }
                // },
           // ],
            // 'encarregado.email' => [
            //     'required',
            //     'unique:users,email',
            //     'email',
            //     function ($attribute, $value, $fail) {
            //         // Get the value of estudante.email
            //         $estudanteEmail = $this->input('estudante.email');

            //         // Check if encarregado.email is equal to estudante.email
            //         if ($value === $estudanteEmail) {
            //             $fail('O Email do encarregado Não pode ser o mesmo Para o Estudante');
            //         }
            //     },
            // ],

            'encarregado.primeiroNome' => 'sometimes|string|max:25',
            'encarregado.ultimoNome' => 'sometimes|string|max:25',
            //'encarregado.email' => 'sometimes|email|unique:users,email',
        ];
    }

    protected function failedValidation(Validator $validator)
{

    //array error response
    // $response = [
    //     'message' => 'Validation failed',
    //     'errors' => $validator->errors(),
    // ];

  //first error response 
    $response = $validator->errors()->first();


    throw new HttpResponseException(response()->json($response, 422));
}

}
