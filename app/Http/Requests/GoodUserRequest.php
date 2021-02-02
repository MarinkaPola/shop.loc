<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodUserRequest extends FormRequest
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
                'user_id'=> 'required|integer|exists:users,id',
                'good_id'=> 'required|integer|exists:goods,id',
                'count'=> 'required|integer|min:1|max:10',
            ];
        }
        elseif ($this->isMethod('put')) {
            $rules = [
                'user_id'=> 'required|integer|exists:users,id',
                'good_id'=> 'required|integer|exists:goods,id',
                'count'=> 'required|integer|min:0|max:10',
            ];
        }
        return $rules;
    }
}
