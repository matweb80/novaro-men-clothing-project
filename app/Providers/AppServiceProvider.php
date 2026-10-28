<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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
        $this->app->booted(function () {
            foreach (Permission::all() as $permission) {
                Gate::before(function ($user) {
                    if ($user->isSuperUser()) return true;
                });
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        });

        $this->app->booted(function () {
            $colors = Setting::whereIn('key', [
                'primary_color',
                'secondary_color',
                'accent_color',
                'danger_color',
                'shadow_color',
            ])->pluck('value', 'key');
            view()->share('themeColors', $colors);
        });

        if (! app()->runningInConsole() && !Cache::has('sale_expiration_checked')) {
            Cache::put('sale_expiration_checked', true, now()->addMinutes(2));
            Product::whereNotNull('sale_price')->where('sale_end_at', '<=', now())->update([
                'sale_price' => null,
                'sale_start_at' => null,
                'sale_end_at' => null,
                'is_on_special_sale' => 0,
                'updated_at' => now(),
            ]);
        }
    }
}
