<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                    'nom'=>'required',
                    'prenom'=>'required',
                    'email'=>'required|unique:users,email',
                    'password'=>'required'
        ];
    }


    public function failedValidation(Validator $validator){
        throw new HttpResponseException( response()->json([
           'success'=>false,
           'status_code'=>422,
           'error'=>true,
           'message'=>'Erreur de validation',
           'errorsList' =>$validator->errors()
        ]));
}

// fonction pour traduire les message d'ereur en francais
public function messages()
{
             return [
                 'nom.required' =>'Veillez renseignée votre nom',
                 'prenom.required' =>'Veuillez renseignée votre prenom',
                 'email.required' =>'une adresse  email doit etre fournie',
                 'email.unique' =>'l\adresse email existe déjat',
                 'password.required' =>'Le mot de passe est requis',
             ];
}
}
