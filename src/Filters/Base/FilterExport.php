<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FilterExport implements FromCollection, WithHeadings
{
    /** @var LengthAwarePaginator|Collection  */
    protected $collection;

    /** @var array<string> */
    protected $headers;

    /**
     * @param $collection
     * @param array $headers
     */
    public function __construct($collection, array $headers)
    {
        $this->collection = $collection;
        $this->headers = $headers;
    }

    /**
     * @return LengthAwarePaginator|Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headers;
    }
}
