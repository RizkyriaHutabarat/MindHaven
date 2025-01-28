<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Namespace untuk controller rute.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Menentukan path default untuk aplikasi.
     *
     * @var string
     */
    const HOME = '/home';

    /**
     * Mendaftarkan rute untuk aplikasi.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Mendaftarkan rute web.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Mendaftarkan rute API.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')  // Menambahkan prefix /api untuk semua rute di api.php
            ->middleware('api')  // Menggunakan middleware api (rate limiting, dll)
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
