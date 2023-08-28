<?php

namespace Wepa\Procedures\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Wepa\Procedures\Models\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        Validator::extend('has_procedures', function ($attribute, $value) {
            return ! Category::hasProcedures($value);
        });

        return [
            'parent_id' => 'numeric|nullable|has_procedures',
            'name' => 'string|required',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'parent_id.has_procedures' => __('procedures::category.has_procedures', ['category' => $this->name]),
        ]);
    }
}
