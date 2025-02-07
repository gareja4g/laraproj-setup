<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExampleRequest extends FormRequest
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
        $id = $this->route("id");
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'max:255', 'email', $id ? "unique:users,email,{$id}" : 'unique:users,email'],
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'gender' => ['required', 'in:Male,Female'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'education' => ['required', 'array', 'min:1'],
            'education.*' => ['string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email address is already registered.',

            'phone.required' => 'The phone field is required.',
            'phone.numeric' => 'The phone must be a valid number.',
            'phone.digits_between' => 'The phone number must be between 10 and 15 digits.',

            'gender.required' => 'The gender field is required.',
            'gender.in' => 'The gender must be either Male or Female.',

            'education.required' => 'Please select at least one educational qualification.',
            'education.array' => 'Please select valid educational qualifications.',
            'education.min' => 'Please select at least one educational qualification.',
            'education.*.string' => 'Each selected qualification must be a valid string.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png.',
            'image.max' => 'The image must not exceed 2MB.',

            'file.file' => 'The uploaded file must be a valid file.',
            'file.mimes' => 'The file must be of type: pdf.',
            'file.max' => 'The file must not exceed 2MB.',

            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
