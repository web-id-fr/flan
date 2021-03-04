<?php

namespace WebId\Flan\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use WebId\Flan\Filters\Base\FilterExport;
use WebId\Flan\Filters\Requests\FilterRequest;

class ExportController extends Controller
{
    /**
     * @param FilterRequest $request
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(FilterRequest $request)
    {
        $filter = $request->getFilter();

        /** @var Collection $models */
        $models = $filter->apply($request->validated());
        $headers = $filter->getColumnsNames();

        if (! $filter->haveColumn($filter->getModelKeyName())) {
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
        $name = Str::ucfirst($request->input('filter_name'));

        return str_replace(' ', '_', $name);
    }
}
