<?php

namespace WebId\Flan\Filters\Requests;

use WebId\Flan\Filters\Base\FieldFactory;
use WebId\Flan\Filters\Base\Filter;
use WebId\Flan\Filters\Base\FilterFactory;
use Illuminate\Foundation\Http\FormRequest;
use WebId\Flan\Filters\Services\MagicCollector;

class FilterRequest extends FormRequest
{
    /** @var Filter */
    protected $filter;

    /**
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    public function rules()
    {
        $config = $this->getFilterConfiguration();
        $availableColumns = implode(',', array_column($config, 'name'));

        $rules = [
            'page' => 'integer' . ($this->expectsJson() ? '|required' : ''),
            'rowsPerPage' => 'integer' . ($this->expectsJson() ? '|required' : ''),
            'fields' => 'required|array',
            'fields.*' => 'in:' . $availableColumns,
            'sortBy' => 'nullable|string|in:' . $availableColumns,
            'descending' => 'boolean',
        ];

        foreach ($config as $field) {
            $fieldName = $field['name'];

            $validations = FieldFactory::getClass($field['field']['type']);
            $validations = $validations::getRules($fieldName);

            $rules = array_merge($rules, $validations);
        }

        return $rules;
    }

    /**
     * @return array<int, array>
     * @throws \Exception
     */
    protected function getFilterConfiguration(): array
    {
        if ($this->input('filter_name') &&
            in_array($this->input('filter_name'), array_keys(MagicCollector::getClasses()))
        ) {
            $this->filter = FilterFactory::create($this->input('filter_name'));

            return $this->filter->getConfiguration()['filters'];
        }

        throw new \Exception("Input 'filter_name' is required and must exist in FilterFactory");
    }
}
