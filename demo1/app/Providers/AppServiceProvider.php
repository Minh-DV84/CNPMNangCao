<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

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
        //
        Paginator::useBootstrap();

        // View composer: mỗi lần render layout thì nạp menu từ CSDL,
        // nhờ đó không controller nào phải tự truyền menu xuống view.
        View::composer('layouts.layoutmaster', function ($view) {
            $view->with('menuHienThi', Menu::where('trang_thai', true)
                ->orderBy('thu_tu')
                ->orderBy('id')
                ->get()
                ->groupBy('vi_tri'));
        });
    }
}
