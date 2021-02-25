<?php

namespace WebId\Flan\Http\Controllers;

use App\Http\Controllers\Controller;
use WebId\Flan\Filters\Base\FilterExport;
use WebId\Flan\Filters\Requests\FilterRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FilterController extends Controller
{
    /**
     * @param FilterRequest $request
     * @return BinaryFileResponse
     */
    public function export(FilterRequest $request)
    {
        $filter = $request->getFilter();

        /** @var Collection $customers */
        $models = $filter->apply($request->validated());
        $headers = $filter->getColumnsNames();

        if (!$filter->haveColumn($filter->getModelKeyName())) {
            array_unshift($headers, $filter->getModelKeyName());
        }

        $fileName = $this->getFilterName($request) . '_' . Carbon::now()->format('Y-m-d');

        return Excel::download(new FilterExport($models, $headers), $fileName .'.xlsx');
    }

    /**
     * @param FilterRequest $request
     * @return string
     */
    private function getFilterName(FilterRequest $request): string
    {
        /** @var string $name */
        $name = config('filters.names.' . $request->input('filter_name'));

        return str_replace(' ', '_', $name);
    }
}
