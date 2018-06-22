<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class OrderRequest extends Request
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
        $rules = [
            'name' => 'required',
        ];
        foreach ($this->request->get('option_name') as $key=>$value){
            $rules['option_name.'.$key] = 'required';
        }
        return $rules;
    }
    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('option_name') as $key=>$value){
            $messages['option_name.'.$key.'max'] = 'The option field is required.';
        }
        return $messages;
    }
}
