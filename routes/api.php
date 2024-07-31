<?php

use App\Http\Controllers\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('user',UserControler::class);