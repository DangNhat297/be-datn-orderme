<?php

namespace App\Providers;

use App\Rules\CheckDateProgram;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(L5SwaggerServiceProvider::class);

        $this->app->bind('path.public', function () {
            return base_path('public_html');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('check_date_program', function ($attribute, $value, $parameters, $validator) {
            $rule = (new CheckDateProgram($parameters[0] ?? null));

            return $rule->passes($attribute, $value);
        });
    }
}
