<?php

namespace WebId\Flan\Filters\Repositories;

use WebId\Flan\Filters\Models\Filter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FilterRepository
{
    /** @var Filter */
    protected $model;

    /**
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {
        $this->model = $filter;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param string $filter_name
     * @return Collection<Filter>
     */
    public function getByFilterName(string $filter_name): Collection
    {
        if (empty($filter_name)) {
            return $this->all();
        }

        return $this->model->where('filter_name', $filter_name)->get();
    }

    /**
     * @param array<string, string> $filterData
     * @param array<string, string> $filterFieldsData
     * @return Filter
     */
    public function create(array $filterData, array $filterFieldsData = [])
    {
        DB::beginTransaction();

        /** @var Filter $filter */
        $filter = $this->model->create($filterData);

        $formatedFieldsData = collect($filterFieldsData)
            ->map(function ($value, $key) {
                return [
                    'name' => $key,
                    'content' => $value,
                ];
            });

        $filter->fields()->createMany($formatedFieldsData->toArray());

        DB::commit();

        return $filter;
    }

    /**
     * @param Filter $filter
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Filter $filter)
    {
        return $filter->delete();
    }
}
