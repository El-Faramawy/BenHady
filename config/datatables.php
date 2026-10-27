<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Yajra\DataTables\ApiResourceDataTable;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\PaginatorDataTable;
use Yajra\DataTables\QueryDataTable;

return [
    /*
     * DataTables search options.
     */
    'search' => [
        'smart' => true,
        'multi_term' => true,
        'case_insensitive' => true,
        'use_wildcards' => false,
        'starts_with' => false,
    ],

    /*
     * DataTables internal index id response column name.
     */
    'index_column' => 'DT_RowIndex',

    /*
     * List of available builders for DataTables.
     * This is where you can register your custom DataTables builder.
     */
    'engines' => [
        'eloquent' => EloquentDataTable::class,
        'query' => QueryDataTable::class,
        'collection' => CollectionDataTable::class,
        'paginator' => PaginatorDataTable::class,
        'resource' => ApiResourceDataTable::class,
    ],

    /*
     * DataTables accepted builder to engine mapping.
     */
    'builders' => [
        Relation::class => 'eloquent',
        EloquentBuilder::class => 'eloquent',
        QueryBuilder::class => 'query',
        Collection::class => 'collection',
        LengthAwarePaginator::class => 'paginator',
    ],

    /*
     * Nulls last sql pattern for PostgreSQL & Oracle.
     */
    'nulls_last_sql' => ':column :direction NULLS LAST',

    /*
     * User friendly message to be displayed on user if error occurs.
     */
    'error' => env('DATATABLES_ERROR', null),

    /*
     * Default columns definition of DataTable utility functions.
     */
    'columns' => [
        'excess' => ['rn', 'row_num'],
        'escape' => '*',
        'raw' => ['action'],
        'blacklist' => ['password', 'remember_token'],
        'whitelist' => '*',
    ],

    /*
     * JsonResponse header and options config.
     */
    'json' => [
        'header' => [],
        'options' => 0,
    ],

    /*
     * Default condition to determine if a parameter is a callback or not.
     */
    'callback' => ['$', '$.', 'function'],
];
