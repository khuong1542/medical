<?php

namespace App\Http\Requests\Admin\Facility;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFacilityRequest extends FormRequest
{
	/**
	* Determine if the user is authorized to make this request.
	*/
	public function authorize(): bool
	{
		return false;
	}

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, ValidationRule|array|string>
	*/
	public function rules(): array
	{
		return [
			// 'name' => 'required',
			// 'description' => 'nullable',
		];
	}

	/**
	* @return array<string, string>
	*/
	public function messages(): array
	{
		return [
			// 'name.required' => 'Name is required',
		];
	}
}