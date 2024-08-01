<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=>['required','string','min:3','max:255'],
            'lastname'=>['required','string','min:3','max:255'],
            'email'=>['required','email','unique:users,email,'.$this->id,'min:3','max:255'],
            'password'=>['required','string','min:8','max:18'],
        ];
    }

    protected function failedValidation(Validator $validator){
        $response=response()->json([
            'data'=>'',
            'msg'=>$validator->errors()
        ],Response::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
