<?php

namespace App\Providers;

use App\Models\Post_Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

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
        View::composer('components.layout', function (ViewInstance $view): void {
            $view->with('category_names', Post_Category::query()->pluck('name'));
        });
    }
}
