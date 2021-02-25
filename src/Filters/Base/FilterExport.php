<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FilterExport implements FromCollection, WithHeadings
{
    /** @var LengthAwarePaginator<Model>|Collection<Model>  */
    protected $collection;

    /** @var array<string> */
    protected $headers;

    /**
     * @param LengthAwarePaginator<Model>|Collection<Model> $collection
     * @param array<string> $headers
     */
    public function __construct($collection, array $headers)
    {
        $this->collection = $collection;
        $this->headers = $headers;
    }

    /**
     * @return LengthAwarePaginator<Model>|Collection<Model>
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return array<string>
     */
    public function headings(): array
    {
        return $this->headers;
    }
}
