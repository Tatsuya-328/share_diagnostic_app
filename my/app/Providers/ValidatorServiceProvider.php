<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Validator\CustomValidator;
use Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::replacer('digits', function($message, $attribute, $rule, $parameters){
            return str_replace(':digits', $parameters[0], $message);
        });

        Validator::replacer('digits_between', function($message, $attribute, $rule, $parameters){
            $message = str_replace(':min_digits', $parameters[0], $message);
            return str_replace(':max_digits', $parameters[1], $message);
        });

         Validator::extend('max_count', function($attribute, $value, $parameters, $validator) {
            return count($value) <= $parameters[0];
        });

        Validator::extend('banned_email', function($attribute, $value, $parameters, $validator) {
            return !User::withTrashed()->where('email', $value)->where('is_ban', true)->exists();
        });

        Validator::extend('duplicated_member', function($attribute, $value, $parameters, $validator) {
            return !User::withTrashed()->where('email', $value)->get()->filter(function($user) use($parameters) {
                return $user->isStatusMember() && $user->isCompanyMember($parameters[0]);
            })->count() > 0;
        });

        $this->app['validator']->resolver(function($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
