<?php

namespace App\Providers;

use App\Exceptions\ApiHandler;
use App\Http\Responses\Responder;
use App\Http\Responses\ResponsesInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Use the Responder as the concrete implementation for the ResponsesInterface
        $this->app->bind(ResponsesInterface::class, Responder::class);

        if (request()->route()) {
            if (strpos(request()->route()->getPrefix(), 'api') !== false) {
                // Use the ApiHandler as the main exception handler
                $this->app->singleton(ExceptionHandler::class, ApiHandler::class);
            }
        }

        if (request()->wantsJson() || request()->getContentType() == 'json') {
            // Use the ApiHandler as the main exception handler
            $this->app->singleton(ExceptionHandler::class, ApiHandler::class);
        }
    }
}
