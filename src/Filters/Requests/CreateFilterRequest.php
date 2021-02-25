<?php

namespace WebId\Flan\Filters\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use WebId\Flan\Filters\Services\MagicCollector;

class CreateFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|string>
     */
    public function rules()
    {
        return [
            'label' => 'required|string|unique:filters,label',
            'filter_name' => [
                'required',
                Rule::in(array_keys(MagicCollector::getClasses())),
            ],
        ];
    }
}
