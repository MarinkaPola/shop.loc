<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodOrderRequest extends FormRequest
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
               // 'order_id'=> 'required|integer|exists:orders,id',
                'good_id'=> 'required|integer|exists:goods,id',
                'count'=> 'required|integer|min:1|max:10',
            ];
        }
        elseif ($this->isMethod('put')) {
            $rules = [
               // 'order_id'=> 'required|integer|exists:orders,id',
                'good_id'=> 'required|integer|exists:goods,id',
                'count'=> 'required|integer|min:0|max:10',
            ];
        }
        return $rules;
    }
}
