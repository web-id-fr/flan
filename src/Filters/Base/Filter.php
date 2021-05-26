<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Filter
{
    use SelectMutations;

    /** @var Model */
    protected $model;

    /** @var Builder */
    protected $query;

    /** @var array<string> */
    protected $columns = [];

    /** @var array<string> */
    protected $joins = [];

    /** @var array<string, mixed> */
    protected $filters;

    /** @var string */
    protected $orderBy;

    /** @var string */
    protected $sort = 'asc';

    /** @var array<int, array> */
    protected $definition;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->query = DB::table($this->model->getTable());

        $this->definition = $this->getConfiguration()['filters'];
    }

    /**
     * Retourne la configuration d'origine du filtre
     *
     * @return array<string, mixed>
     */
    abstract public function getConfiguration(): array;

    /**
     * Lance un filtrage par rapport aux paramètres $inputs
     *
     * @param array<string, mixed> $inputs
     * @return LengthAwarePaginator|Collection
     */
    public function apply(array $inputs)
    {
        $build = $this
            ->select($inputs['fields'])
            ->filter($this->getUsableFilters($inputs))
            ->orderBy($inputs['sortBy'] ?? null)
            ->sort($inputs['descending'] ?? false);

        if (! $this->haveColumn($this->getModelKeyName())) {
            $this->query->select($this->model->getTable() .'.'. $this->getModelKeyName());
        }

        return isset($inputs['rowsPerPage']) ?
            $build->paginate($inputs['rowsPerPage']) :
            $build->get();
    }

    /**
     * @param string $column
     * @return bool
     */
    public function haveColumn(string $column): bool
    {
        return in_array($column, $this->columns);
    }

    /**
     * @return string
     */
    public function getModelKeyName(): string
    {
        return $this->model->getKeyName();
    }

    /**
     * @return mixed
     */
    public function getColumnsNames()
    {
        return collect($this->getConfiguration()['filters'])
            ->whereIn('name', $this->columns)
            ->pluck('text')
            ->all();
    }

    /**
     * @param array<string> $columnsWanted
     * @return $this
     */
    public function select(array $columnsWanted)
    {
        $this->columns = $columnsWanted;

        return $this;
    }

    /**
     * @param array<string, mixed> $filters
     * @return $this
     */
    public function filter(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param string|null $column
     * @return $this
     */
    public function orderBy(string $column = null)
    {
        if (! $column) {
            $this->orderBy = $this->model->getTable() .'.'. $this->model->getKeyName();

            return $this;
        }

        $this->orderBy = $column;

        $field = $this->getFieldByName($column);
        if (in_array($this->getFieldType($field), ['date', 'datetime'])) {
            $this->orderBy = $this->getFieldColumnName($field);
        }

        return $this;
    }

    /**
     * @param bool $sort
     * @return $this
     */
    public function sort(bool $sort = false)
    {
        $this->sort = $sort ? 'desc' : 'asc';

        return $this;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return $this->buildQuery()->get();
    }

    /**
     * @param int $number
     * @return LengthAwarePaginator
     */
    public function paginate(int $number)
    {
        $number = $number > 0 ? $number : $this->query->count();

        return $this->buildQuery()->paginate($number);
    }

    /**
     * Set les $value sur le(s) field(s) qui a le name $key
     *
     * @param array<string>|string $key
     * @param array<string, mixed> $value
     */
    protected function setDefinition($key, array $value): void
    {
        $key = is_array($key) ? $key : [$key];

        $this->definition = array_map(function ($field) use ($key, $value) {
            if (in_array($this->getFieldName($field), $key)) {
                $field = array_merge($field, $value);
            }

            return $field;
        }, $this->definition);
    }

    /**
     * @return Builder
     */
    private function buildQuery()
    {
        $this->querySelects();

        $this->querySearches();

        if ($this->orderBy) {
            $this->query->orderBy($this->orderBy, $this->sort);
        }

        $this->addColumnsToGroupByIfUsed();

        return $this->query;
    }

    /**
     * Ajoute les statements SELECT et JOIN à la requête
     */
    private function querySelects(): void
    {
        foreach ($this->columns as $column) {
            $field = $this->getFieldByName($column);
            $tableName = $this->getFieldTableName($field);

            $this->query->addSelect($this->mutateSelect($tableName, $field));

            $this->joinIfNeeded($field);
        }
    }

    /**
     * Ajoute les statements WHERE à la requête
     */
    private function querySearches(): void
    {
        $filters = $this->filterSearchesByActiveColumns();

        foreach ($filters as $column => $search) {
            $field = $this->getFieldByName($column);
            $column = $this->getQueryableColumnNameForWhereStatement($field);

            $fieldClass = FieldFactory::create($this->getFieldType($field), $this->query);

            $fieldClass->query($search, $column);
        }

        $this->queryNotSoftDeleted();
    }

    /**
     * @param string $name
     * @return array<string, mixed>
     */
    private function getFieldByName(string $name): array
    {
        return collect($this->definition)->where('name', $name)->first();
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getFieldTableName(array $field): string
    {
        return $field['table'] ?? $this->model->getTable();
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getFieldColumnName(array $field): string
    {
        return $field['column_name'] ?? $field['name'];
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getFieldType(array $field): string
    {
        return $field['field']['type'] ?? '';
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getFieldName(array $field): string
    {
        return $field['name'] ?? '';
    }

    /**
     * Retourne les champs dans lesquels rechercher, dans la limite des colonnes requises
     *
     * @return array<string, array>
     */
    private function filterSearchesByActiveColumns(): array
    {
        return array_filter($this->filters, function ($key) {
            return in_array($key, $this->columns);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array<string, mixed> $field
     */
    private function joinIfNeeded(array $field): void
    {
        if (isset($field['join']) && ! $this->isAlreadyJoined($field['join'])) {
            $this->{$field['join']}();

            $this->joins[] = $field['join'];
        }
    }

    /**
     * @param string $joinMethod
     * @return bool
     */
    private function isAlreadyJoined(string $joinMethod): bool
    {
        return in_array($joinMethod, $this->joins);
    }

    /**
     * Retourne les filtres qui correspondent à des fields définis dans la config du filtre
     *
     * @param array<string, mixed> $inputs
     * @return array<string, mixed>
     */
    private function getUsableFilters($inputs)
    {
        $names = array_column($this->definition, 'name');

        return array_filter($inputs, function ($key) use ($names) {
            return in_array($key, $names);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function queryNotSoftDeleted(): void
    {
        if (method_exists($this->model, 'getDeletedAtColumn')) {
            $this->query->whereNull($this->model->getTable() . '.' . $this->model->getDeletedAtColumn());
        }
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getQueryableColumnNameForWhereStatement(array $field): string
    {
        if (isset($field['custom_select'])) {
            return $field['custom_select'];
        }

        if (isset($field['sub_select'])) {
            /** @var Builder $builder */
            $builder = $field['sub_select']();
            $queryWithBindings = Str::replaceArray('?', $builder->getBindings(), $builder->toSql());

            return "($queryWithBindings)";
        }

        return '`'. config('database.db_prefix') .
            $this->getFieldTableName($field) .'`.`'.
            $this->getFieldColumnName($field) .'`';
    }

    private function addColumnsToGroupByIfUsed(): void
    {
        if (empty($this->query->groups)) {
            return;
        }

        foreach ($this->columns as $column) {
            $field = $this->getFieldByName($column);
            if ($this->getFieldMutationType($field) !== 'concat') {
                $this->query->groupBy($column);
            }
        }
    }
}
