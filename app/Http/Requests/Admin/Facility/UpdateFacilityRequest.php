<?php

namespace App\Http\Requests\Admin\Facility;

use App\Enums\FacilityEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateFacilityRequest extends FormRequest
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
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'name' => 'required|string|max:255',
			'code' => [
				'required',
				'string',
				Rule::unique('facilities', 'code')->ignore($this->route('id')),
			],
			'type' => ['required', new Enum(FacilityEnum::class)],
			'images' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
			'tax_code' => 'nullable|string|max:50',
			'address' => 'nullable|string',
			'tel' => 'nullable|string|max:20',
			'email' => 'nullable|email',
			'website' => 'nullable|url',
			'map_url' => 'nullable|url',
			'description' => 'nullable|string',
			'order' => 'nullable|integer|min:1',
			'status' => 'nullable',
		];
	}

	/**
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'name.required' => 'Name is required',
			'code.required' => 'Code is required',
			'code.unique' => 'Code already exists',
			'type.required' => 'Type is required',
			'images.image' => 'Images must be an image file',
			'email.email' => 'Email must be a valid email address',
			'website.url' => 'Website must be a valid URL',
			'map_url.url' => 'Map URL must be a valid URL',
			'order.integer' => 'Order must be an integer',
			'order.min' => 'Order must be at least 1',
		];
	}
}
