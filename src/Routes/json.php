<?php

use Illuminate\Support\Facades\Route;
use WebId\Flan\Http\Controllers\FilterController;

if (config('flan.routing.filters.active')) {
    Route::middleware(config('flan.routing.filters.middlewares'))
        ->prefix(config('flan.routing.filters.prefix'))
        ->name(config('flan.routing.filters.name'))
        ->group(function () {
            Route::get('/{filter_name}', [FilterController::class, 'index'])->name('index');
            Route::post('/filter', [FilterController::class, 'filter'])->name('filter');
            Route::post('/', [FilterController::class, 'store'])->name('store');
            Route::delete('/{filter}', [FilterController::class, 'destroy'])->name('destroy');
        });
}

if (config('flan.routing.export.active')) {
    Route::middleware(config('flan.routing.export.middlewares'))
        ->prefix(config('flan.routing.export.prefix'))
        ->name(config('flan.routing.export.name'))
        ->group(function () {
            Route::post('/csv-export', [\WebId\Flan\Http\Controllers\ExportController::class, 'export'])->name('export');
        });
}
