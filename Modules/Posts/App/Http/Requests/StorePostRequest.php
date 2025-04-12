<?php

namespace Modules\Posts\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Posts\Enums\PostPrivacyEnum;

class StorePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'privacy' => 'required|in:'.implode(',', array_column(PostPrivacyEnum::cases(), 'value')),
            'event_name' => 'nullable|string|max:255',
            'event_date_time' => 'nullable|date',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
