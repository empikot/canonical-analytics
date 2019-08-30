<?php

namespace App\IO\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LandingPageStatCreation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'required',
            'url_canonical' => 'nullable',
            'offers_quantity' => 'required|numeric'

        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ['messages' => $validator->getMessageBag()->getMessages()],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
