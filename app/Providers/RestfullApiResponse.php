<?php

namespace App\Providers;

use App\RestFullApi\ApiResponseBuilder;
use Illuminate\Support\ServiceProvider;

class RestfullApiResponse extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('ApiResponse',function(){
            return new ApiResponseBuilder;
        });
    }

    public function boot(): void
    {

    }
}
