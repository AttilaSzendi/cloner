<?php

use App\Http\Controllers\RepositoryController;
use Illuminate\Support\Facades\Route;

Route::resource('/repositories', RepositoryController::class)->only(['index', 'store']);
