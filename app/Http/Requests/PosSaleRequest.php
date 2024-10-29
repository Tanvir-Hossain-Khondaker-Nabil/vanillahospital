<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosSaleRequest extends FormRequest
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
        return [
//            'memo_no' => 'required',
            'date' => 'required',
            'cash_or_bank_id' => 'required',
            'payment_method_id' => 'required',
//            'customer_id' => 'required_if:paid_amount,<,total_amount',
            "product_id"    => "required|array|min:1",
            "product_id.*"  => "required|numeric",
            "qty"    => "required|array|min:1",
            "qty.*"  => "required|numeric",
            "price"    => "required|array|min:1",
            "price.*"  => "required|numeric",
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
//            'memo_no.required' => ':attribute is required',
            'date.required' => ':attribute is required',
            'cash_or_bank_id.required' => ':attribute is required',
//            'customer_id.required' => ':attribute is required if you paid amount less than total amount',
            'payment_method_id.required' => ':attribute is required',
            'product_id.required' => ':attribute is required',
            'qty.required' => ':attribute is required',
            'price.required' => ':attribute is required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'memo_no' => 'memo no',
            'cash_or_bank_id' => 'account',
            'customer_id' => 'customer',
            'payment_method_id' => 'payment method',
            'product_id' => 'product',
        ];
    }
}
