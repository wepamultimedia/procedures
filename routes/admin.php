<?php

use Wepa\Procedures\Http\Controllers\Backend\CategoryController;
use Wepa\Procedures\Http\Controllers\Backend\ProcedureController;
use Wepa\Procedures\Http\Controllers\Backend\ProcedureFileController;

Route::prefix('admin/procedures')->middleware(['web', 'auth:sanctum'])->group(function () {
    Route::resource('categories', CategoryController::class)
        ->names('admin.procedures.categories');

    Route::get('categories/create/{parent?}', [CategoryController::class, 'create'])->name('admin.procedures.categories.create');

    Route::put('categories/position/{category}/{position}', [CategoryController::class, 'position'])
        ->name('admin.procedures.categories.position');

    Route::resource('procedures', ProcedureController::class)->names('admin.procedures.procedures');
    Route::put('position/{category}/{position}', [CategoryController::class, 'position'])
        ->name('admin.procedures.procedures.position');

    Route::resource('procedures/files', ProcedureFileController::class)->names('admin.procedures.files');
});
