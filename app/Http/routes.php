<?php

use App\Http\Controllers\ArticleController;
use Src\Route;

Route::get('/', [ArticleController::class, 'index']);
