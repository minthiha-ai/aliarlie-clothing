<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
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
            'billing_first_name' => ['required', 'string', 'max:255'],
            'billing_last_name' => ['required', 'string', 'max:255'],
            'billing_phone' => ['required', 'string', 'max:50'],
            'billing_email' => ['required', 'email', 'max:255'],
            'billing_address_line1' => ['required', 'string', 'max:255'],
            'billing_address_line2' => ['nullable', 'string', 'max:255'],
            'billing_state_region_id' => ['required', 'integer', 'exists:state_regions,id'],
            'billing_township_id' => [
                'required',
                'integer',
                'exists:townships,id',
                Rule::exists('townships', 'id')->where('state_region_id', $this->input('billing_state_region_id')),
            ],
            'billing_postal_code' => ['nullable', 'string', 'max:50'],
            'billing_country' => ['required', 'string', 'max:100'],
            'ship_to_different' => ['nullable', 'boolean'],
            'shipping_first_name' => ['nullable', 'required_if:ship_to_different,1', 'string', 'max:255'],
            'shipping_last_name' => ['nullable', 'required_if:ship_to_different,1', 'string', 'max:255'],
            'shipping_phone' => ['nullable', 'required_if:ship_to_different,1', 'string', 'max:50'],
            'shipping_address_line1' => ['nullable', 'required_if:ship_to_different,1', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'shipping_township' => ['nullable', 'required_if:ship_to_different,1', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'required_if:ship_to_different,1', 'string', 'max:255'],
            'shipping_postal_code' => ['nullable', 'string', 'max:50'],
            'shipping_country' => ['nullable', 'string', 'max:100'],
            'order_notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'in:cod,online_payment'],
            'payment_id' => ['required_if:payment_method,online_payment', 'nullable', 'integer', 'exists:payments,id'],
            'payment_proof_photo' => ['required_if:payment_method,online_payment', 'nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'billing_first_name.required' => 'Please enter your billing first name.',
            'billing_last_name.required' => 'Please enter your billing last name.',
            'billing_phone.required' => 'Please enter a billing phone number.',
            'billing_email.required' => 'Please enter your billing email address.',
            'billing_email.email' => 'Please enter a valid billing email address.',
            'billing_address_line1.required' => 'Please enter your billing street address.',
            'billing_state_region_id.required' => 'Please select your state / region.',
            'billing_township_id.required' => 'Please select your township.',
            'billing_country.required' => 'Please select your billing country.',
            'shipping_first_name.required_if' => 'Please enter the shipping first name.',
            'shipping_last_name.required_if' => 'Please enter the shipping last name.',
            'shipping_phone.required_if' => 'Please enter the shipping phone number.',
            'shipping_address_line1.required_if' => 'Please enter the shipping street address.',
            'shipping_township.required_if' => 'Please enter the shipping township.',
            'shipping_city.required_if' => 'Please enter the shipping city.',
            'payment_method.required' => 'Please choose a payment method.',
            'payment_method.in' => 'Please select a valid payment method.',
            'payment_id.required_if' => 'Please select an online payment account.',
            'payment_id.exists' => 'The selected payment account is unavailable.',
            'payment_proof_photo.required_if' => 'Please upload your payment proof for online payments.',
            'payment_proof_photo.image' => 'Payment proof must be an image file.',
            'payment_proof_photo.max' => 'Payment proof must be smaller than 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'billing_first_name' => 'billing first name',
            'billing_last_name' => 'billing last name',
            'billing_phone' => 'billing phone',
            'billing_email' => 'billing email',
            'billing_address_line1' => 'billing address',
            'billing_state_region_id' => 'state / region',
            'billing_township_id' => 'township',
            'shipping_first_name' => 'shipping first name',
            'shipping_last_name' => 'shipping last name',
            'shipping_phone' => 'shipping phone',
            'shipping_address_line1' => 'shipping address',
            'shipping_township' => 'shipping township',
            'shipping_city' => 'shipping city',
        ];
    }
}
