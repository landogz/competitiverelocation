<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'ext' => 'nullable|string|max:10',
            'from_areacode' => 'required|string|max:10',
            'from_zip' => 'required|string|max:10',
            'from_state' => 'required|string|max:2',
            'from_city' => 'required|string|max:255',
            'to_areacode' => 'required|string|max:10',
            'to_zip' => 'required|string|max:10',
            'to_state' => 'required|string|max:2',
            'to_city' => 'required|string|max:255',
            'distance' => 'required|numeric|min:0',
            'move_date' => 'required|date|after:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'phone.required' => 'Please enter your phone number',
            'from_areacode.required' => 'Please enter the area code for your current location',
            'from_zip.required' => 'Please enter the ZIP code for your current location',
            'from_state.required' => 'Please enter the state for your current location',
            'from_city.required' => 'Please enter the city for your current location',
            'to_areacode.required' => 'Please enter the area code for your destination',
            'to_zip.required' => 'Please enter the ZIP code for your destination',
            'to_state.required' => 'Please enter the state for your destination',
            'to_city.required' => 'Please enter the city for your destination',
            'distance.required' => 'Please enter the distance',
            'distance.numeric' => 'Distance must be a number',
            'distance.min' => 'Distance cannot be negative',
            'move_date.required' => 'Please select a move date',
            'move_date.date' => 'Please enter a valid date',
            'move_date.after' => 'Move date must be a future date',
        ];
    }
}
