<?php

namespace App\Http\Requests;

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
        if ($this->isMethod('post')) {
            $rules = [
                'payment' => [
                    'required',
                    Rule::in(['cod', 'cash', 'paymentByCard']),
                ],
                'delivery' =>[
                    'required',
                    Rule::in(['pickup', 'courierDelivery']),
                ],
                'goods_is_paid' => 'required|boolean',
                'buyer_id' => 'required|integer|exists:users,id',
                'sum' => 'required|numeric',
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'payment' => [
                    'required',
                    Rule::in(['cod', 'cash', 'paymentByCard']),
                ],
                'delivery' =>[
                    'required',
                    Rule::in(['pickup', 'courierDelivery']),
                ],
                'goods_is_paid' => 'required|boolean',
                'buyer_id' => 'required|integer|exists:users,id',
                'sum' => 'required|numeric',
            ];
        }
        return $rules;
    }

}
