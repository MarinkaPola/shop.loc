<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->isMethod('put')) {
            $rules = [
                'payment' => [
                    'required',
                    Rule::in(Order::PAYMENT),
                ],
                'delivery' =>[
                    'required',
                    Rule::in(Order::DELIVERY),
                ],
                'info' => 'required|string|min:10|max:150',
            ];
        }
        return $rules;
    }

}
