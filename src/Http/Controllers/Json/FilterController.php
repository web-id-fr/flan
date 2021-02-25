<?php

namespace WebId\Flan\Http\Controllers\Json;

use WebId\Flan\Filters\Models\Filter;
use WebId\Flan\Filters\Repositories\FilterRepository;
use WebId\Flan\Filters\Requests\CreateFilterRequest;
use WebId\Flan\Filters\Resources\SavedFilterResource;
use App\Http\Controllers\Controller;
use WebId\Flan\Filters\Requests\FilterRequest;
use WebId\Flan\Filters\Resources\FilterResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterController extends Controller
{
    /** @var FilterRepository */
    protected $filterRepository;

    public function __construct(FilterRepository $repository)
    {
        $this->filterRepository = $repository;
    }

    /**
     * @param string $filter_name
     * @return AnonymousResourceCollection
     */
    public function index(string $filter_name)
    {
        $filters = $this->filterRepository->getByFilterName($filter_name);
        return SavedFilterResource::collection($filters);
    }

    /**
     * @param FilterRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function filter(FilterRequest $request)
    {
        $models = $request->getFilter()->apply($request->all());
        return FilterResource::collection($models);
    }

    /**
     * @param Filter $filter
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Filter $filter)
    {
        $this->filterRepository->delete($filter);
        return response()->json([], config('httpstatus.deleted'));
    }

    /**
     * @param CreateFilterRequest $request
     * @return SavedFilterResource
     */
    public function store(CreateFilterRequest $request)
    {
        $newFilter = $this->filterRepository
            ->create($request->except('fields'), $request->fields);
        return SavedFilterResource::make($newFilter);
    }
}
