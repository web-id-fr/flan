<?php

namespace WebId\Flan;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('flan_integer_if', function ($attribute, $value, $parameters, $validator) {
            if (empty($parameters) || count($parameters) === 1) {
                return true;
            }
            $attributes = $validator->attributes();
            $targetRule = array_shift($parameters);
            $targetRuleValue = data_get($attributes, $targetRule);
            if (in_array($targetRuleValue, $parameters)) {
                return ctype_digit($value);
            }
            return true;
        });

        Validator::extend('flan_array_if', function ($attribute, $value, $parameters, $validator) {
            if (empty($parameters) || count($parameters) === 1) {
                return true;
            }
            $attributes = $validator->attributes();
            $targetRule = array_shift($parameters);
            $targetRuleValue = data_get($attributes, $targetRule);
            if (in_array($targetRuleValue, $parameters)) {
                return is_array($value);
            }
            return true;
        });
    }
}
