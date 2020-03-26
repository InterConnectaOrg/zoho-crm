<?php

namespace Zoho\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clientid' => 'required',
            'clientsecret' => 'required',
            'redirecturi' => 'required',
            'accesstype' => 'required',
            'scope' => 'required',
            'email' => 'required|email',
        ];
    }
}
