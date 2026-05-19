<?php

namespace App\Http\Requests\Admin\Facility;

use App\Enums\FacilityEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreFacilityRequest extends FormRequest
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
			'images' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
			'name.required' => __('admin/messages.facility.validation.required', ['name' => __('admin/messages.facility.name')]),
			'code.required' => __('admin/messages.facility.validation.required', ['name' => __('admin/messages.facility.code')]),
			'type.required' => __('admin/messages.facility.validation.required', ['name' => __('admin/messages.facility.type')]),
			'images.required' => __('admin/messages.facility.validation.required', ['name' => __('admin/messages.facility.images')]),
			'images.image' => __('admin/messages.facility.validation.image', ['name' => __('admin/messages.facility.images')]),
			'email.email' => __('admin/messages.facility.validation.email', ['name' => __('admin/messages.facility.email')]),
			'website.url' => __('admin/messages.facility.validation.url', ['name' => __('admin/messages.facility.website')]),
			'map_url.url' => __('admin/messages.facility.validation.url', ['name' => __('admin/messages.facility.map_url')]),
			'order.integer' => __('admin/messages.facility.validation.integer', ['name' => __('admin/messages.facility.order')]),
			'order.min' => __('admin/messages.facility.validation.min', ['name' => __('admin/messages.facility.order'), 'min' => 1]),
		];
	}
}
