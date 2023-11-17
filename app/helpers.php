<?php

use Illuminate\Support\Facades\Schema;

if (!function_exists('getTableSchema')) {
    function convertSchemaColumnTypeToPHP($type)
    {
        $typeMapping = [
            'text' => 'string',
            'date' => 'string',
            'datetime' => 'string',
        ];

        return $typeMapping[$type] ?? $type;
    }

    function getTableSchema($table)
    {
        $schema = [];
        $columns = Schema::getColumnListing($table);

        foreach($columns as $column) {
            $type = Schema::getColumnType($table, $column);
            $schema[$column] = convertSchemaColumnTypeToPHP($type);
        }

        return $schema;
    }
}
