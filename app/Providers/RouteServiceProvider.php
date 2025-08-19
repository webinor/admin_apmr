<?php

namespace App\Providers;

use App\Models\AssistanceAgent;
use App\Models\City;
use App\Models\Company;
use App\Models\GroundAgent;
use App\Models\Misc\Bank;
use App\Models\Misc\File;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Misc\Currency;
use App\Models\Misc\Resource;
use App\Models\Misc\Observation;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\Transfert;
use Illuminate\Support\Facades\Route;
use App\Models\HumanResource\Employee;
use App\Models\Misc\Payment;
use App\Models\Operations\Folder;
use App\Models\Operations\Provider;
use App\Models\Operations\Slip;
use App\Models\Prestations\Product;
use App\Models\Prestations\Service;
use App\Models\Settings\Area;
use App\Models\Settings\AreaDuration;
use App\Models\Supplier\OrderLine;
use App\Models\WheelChair;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    


        /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const ADMIN_HOME = '/admin';
    public const HOME = '/';


        /**
     * The path to the "login" route for your application.
     *
     * This is used by Laravel retry authentication.
     *
     * @var string
     */
    public const LOGIN = '/login';


        /**
     * The path to the "sms_verify" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const SMS_VERIFY = '/sms_verify';


        /**
     * The path to the "email_verify" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const EMAIL_VERIFY = '/email/verify';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();


        Route::bind('city', function ($value) {
            return City::where('code', $value)->first();
        });

        Route::bind('company', function ($value) {
            return Company::where('code', $value)->first();
        });

        Route::bind('wheel-chair', function ($value) {
            return WheelChair::where('code', $value)->first();
        });


        Route::bind('ground-agent', function ($value) {
            return GroundAgent::where('code', $value)->first();
        });

        Route::bind('assistance-agent', function ($value) {
            return AssistanceAgent::where('code', $value)->first();
        });


        Route::bind('aaaafetch-folder', function ($value) {
            dd($value);
            return Folder::where('code', $value)->first();
        });

       
        


           Route::bind('provider', function ($value) {
           // dd($value);
               return Provider::where('code', $value)->first();
           });

      

           Route::bind('file', function ($value) {
            return File::where('code', $value)->first();
        });

        Route::bind('employee', function ($value) {
            return Employee::where('code', $value)->first();
        });


        Route::bind('user', function ($value) {

            return  Employee::where('code', $value)->with('user')->first()->user;
   
               
           });

          


        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

                Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

               // Route::middleware('web')
               // ->namespace($this->namespace)
               // ->group(base_path('routes/admin.php'));

               // Route::middleware('web')
               // ->namespace($this->namespace)
               // ->group(base_path('routes/user.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('properties', function (Request $request) {
            return Limit::perMinute(2)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
