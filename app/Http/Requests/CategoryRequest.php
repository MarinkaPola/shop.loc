<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                'area_id'=> 'required|integer|exists:areas,id',
        ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'required|string|min:3|max:100',
                'area_id'=> 'required|integer|exists:areas,id',
            ];
        }
            return $rules;
    }

}
