<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait SelectMutations
{
    /** @var Model */
    protected $model;

    /** @var Builder */
    protected $query;

    /**
     * Retourne le statement SELECT à appliquer pour un champ $field donné
     *
     * @param string $tableName
     * @param array<string, mixed> $field
     * @return Expression|string
     */
    private function mutateSelect(string $tableName, array $field)
    {
        if ($this->getFieldMutationType($field) === 'concat') {
            return $this->mutateToConcat($tableName, $field);
        } elseif ($this->getFieldType($field) === "date") {
            return $this->mutateDate($tableName, $field);
        } elseif ($this->getFieldType($field) === "datetime") {
            return $this->mutateDateTime($tableName, $field);
        } elseif (isset($field['custom_select'])) {
            return $this->mutateToCustomSelect($field);
        } elseif (isset($field['sub_select'])) {
            return $this->mutateToSubSelect($field);
        } elseif ($this->fieldHasDataMutation($field)) {
            return $this->mutateToArray($tableName, $field);
        }

        return $tableName .'.'. $this->getColumnName($field) .' AS '. $field['name'];
    }

    /**
     * @param string $tableName
     * @param array<string, mixed> $field
     * @return Expression
     */
    private function mutateToArray(string $tableName, array $field): Expression
    {
        $columnName = $this->getTableAndColumnName($tableName, $field);

        $sql = $this->getCaseWhenFromArray($columnName, $field['mutation']['data']);

        return DB::raw("($sql) AS ". $field['name']);
    }

    /**
     * @param string $tableName
     * @param array<string, mixed> $field
     * @return Expression
     */
    private function mutateToConcat(string $tableName, array $field): Expression
    {
        $columnName = $this->getTableAndColumnName($tableName, $field);

        if ($this->fieldHasDataMutation($field)) {
            $columnName = $this->getCaseWhenFromArray($columnName, $field['mutation']['data']);
        }

        $this->query->groupBy($this->model->getTable() . '.' . $this->model->getKeyName());

        return DB::raw("GROUP_CONCAT($columnName SEPARATOR ', ') AS " . $field['name']);
    }

    /**
     * @param string $tableName
     * @param array<string, mixed> $field
     * @return Expression
     */
    private function mutateDate(string $tableName, array $field): Expression
    {
        $columnName = $this->getTableAndColumnName($tableName, $field);
        $dateFormat = config('flan.default_sql_date_format_output');

        return DB::raw("DATE_FORMAT($columnName, '$dateFormat') AS ". $field['name']);
    }

    /**
     * @param string $tableName
     * @param array<string, mixed> $field
     * @return Expression
     */
    private function mutateDateTime(string $tableName, array $field): Expression
    {
        $columnName = $this->getTableAndColumnName($tableName, $field);
        $dateFormat = config('flan.default_sql_datetime_format_output');

        return DB::raw("DATE_FORMAT($columnName, '$dateFormat') AS ". $field['name']);
    }

    /**
     * @param array<string, mixed> $field
     * @return Expression
     */
    private function mutateToCustomSelect(array $field): Expression
    {
        return DB::raw($field['custom_select'] . ' AS ' . $field['name']);
    }

    /**
     * @param array $field
     * @return Expression
     */
    private function mutateToSubSelect(array $field): Expression
    {
        /** @var Builder $builder */
        $builder = $field['sub_select']();

        $queryWithBindings = Str::replaceArray('?', $builder->getBindings(), $builder->toSql());

        return DB::raw("($queryWithBindings) AS " . $field['name']);
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getFieldMutationType(array $field): string
    {
        return $field['mutation']['type'] ?? '';
    }

    /**
     * @param array<string, mixed> $field
     * @return bool
     */
    private function fieldHasDataMutation(array $field): bool
    {
        return isset($field['mutation']['data']);
    }

    /**
     * @param array<string, mixed> $field
     * @return string
     */
    private function getColumnName(array $field): string
    {
        return $field['column_name'] ?? $field['name'];
    }

    /**
     * @param string $tableName
     * @param array<string, mixed> $field
     * @return string
     */
    private function getTableAndColumnName(string $tableName, array $field): string
    {
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");

        return "`$prefix$tableName`.`". $this->getColumnName($field) ."`";
    }

    /**
     * @param string $columnName
     * @param array<string, string> $data
     * @return string
     */
    private function getCaseWhenFromArray(string $columnName, array $data): string
    {
        $sql = 'CASE ';

        foreach ($data as $original => $mutated) {
            $mutated = str_replace("'", "\'", $mutated);
            $original = str_replace('\\', '\\\\', $original);
            $sql .= "WHEN $columnName = '$original' THEN '$mutated' ";
        }

        return $sql ."ELSE $columnName END";
    }
}
