<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
                'text' => 'required|string|min:10|max:200',
                'mark' => 'required|integer|min:1|max:5'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'text' => 'required|string|min:10|max:200',
                'mark' => 'required|integer|min:1|max:5'
            ];
        }

        return $rules;
    }

}
