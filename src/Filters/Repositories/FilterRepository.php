<?php

namespace WebId\Flan\Filters\Repositories;

use WebId\Flan\Filters\Models\Filter;
use App\Repositories\Traits\DeleteRepositoryTrait;
use App\Repositories\Traits\SelectRepositoryTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FilterRepository
{
    use SelectRepositoryTrait;
    use DeleteRepositoryTrait;

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
     * @param string $filter_name
     * @return Collection|array
     */
    public function getByFilterName(string $filter_name)
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
}
