<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255', 
            'order_date' => 'required|date', 
            'medicines' => 'required|array', 
            'medicines.*.id' => 'required_with:medicines|integer|exists:medicines,id', 
            'medicines.*.quantity' => 'required_with:medicines|integer|min:1',
        ];
    }
}