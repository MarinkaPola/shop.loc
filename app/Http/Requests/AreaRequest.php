<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
{



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->isMethod('post')) {
            $rules = [
                'title' => 'required|string|min:3|max:100',
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'required|string|min:3|max:100',

            ];
        }
        return $rules;
    }

}
