<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(config('app.env') === 'production') {
			\URL::forceScheme('https');
		}

		Gate::define('admin', function(User $user) {
			return $this->checkPermission($user,'admin');
		});
    }

    private function checkPermission ( $user, $permission ) {

        return strcasecmp($user->role, $permission) == 0 ;
	}
}
