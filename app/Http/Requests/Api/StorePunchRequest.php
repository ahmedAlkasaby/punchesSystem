<?php

namespace App\Http\Requests\Api;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePunchRequest extends FormRequest
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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['required', 'string', 'max:255'],
            'device_info' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'latitude.required' => __('validation.required'),
            'latitude.numeric' => __('validation.numeric'),
            'latitude.between' => __('validation.between.numeric', ['attribute' => 'latitude', 'min' => -90, 'max' => 90]),
            'longitude.required' => __('validation.required'),
            'longitude.numeric' => __('validation.numeric'),
            'longitude.between' => __('validation.between.numeric', ['attribute' => 'longitude', 'min' => -180, 'max' => 180]),
            'address.required' => __('validation.required'),
            'address.string' => __('validation.string'),
            'address.max' => __('validation.max.string', ['attribute' =>'address', 'max' => 255]),
            'device_info.string' => __('validation.string'),
            'device_info.max' => __('validation.max.string', ['attribute' => 'device_info', 'max' => 255]),
            'note.string' => __('validation.string'),
            'note.max' => __('validation.max.string', ['attribute' => 'note', 'max' => 1000]),
        ];
    }
}
