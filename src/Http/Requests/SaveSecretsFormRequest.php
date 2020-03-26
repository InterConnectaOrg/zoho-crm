<?php

namespace Zoho\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSecretsFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required',
            'location' => 'required',
        ];
    }
}
