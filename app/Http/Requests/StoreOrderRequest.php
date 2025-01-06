<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
                'customer_name' => 'required|string|max:255', 
                'order_date' => 'required|date', 
                'medicines' => 'required|array', 
                'medicines.*.id' => 'required|integer|exists:medicines,id', 
                'medicines.*.quantity' => 'required|integer|min:1',
        ];
    }
}