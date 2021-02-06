<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodRequest extends FormRequest
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
                'title' => 'required|string|min:2|max:60',
                'photo' =>  'nullable|string|min:8',
                'feature' => 'required|string|min:10|max:200',
                'count' => 'required|integer|min:1|max:10',
                'price' => 'required|numeric|min:1',
                'sale' => 'required|integer|min:0|max:70',
                'category_id' => 'required|integer|exists:categories,id',

            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'required|string|min:2|max:60',
                'photo' =>  'nullable|string|min:8',
                'feature' => 'required|string|min:10|max:200',
                'count' => 'required|integer|min:1|max:10',
                'price' => 'required|numeric|min:1',
                'sale' => 'required|integer|min:0|max:70',
                'category_id' => 'required|integer|exists:categories,id',
            ];
        }
        return $rules;
    }

}
