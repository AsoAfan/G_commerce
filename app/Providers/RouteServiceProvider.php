<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('otpRequest', function () {
            return Limit::perMinutes(5, 3)
                ->response(
                    fn() => response(
                        ['message' => "To many requests try again later", 'code' => 429],
                        status: 429
                    )
                );
        });
//        RateLimiter::for('loginRequest', fn($request) => Limit::perMinutes(10, 5)
//            ->by($request->input('email'))
//            ->response(function (Request $request) {
//
//
//                /**
//                 * @var User $user
//                 */
//                $user = User::find($request->post('email'));
//                try {
//                    $user->notify(new LoginAttemptNotification());
//
//                } catch (Error) {
//                }
////                Notification::route('mail', $request->post('email'))->notify(new LoginAttemptNotification());
//                return response([
//                    'message' => "Too many login attempts. Your account has been temporarily blocked for security reasons",
//                    'code' => 429
//                ], 429);
//            })
//        );


        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
