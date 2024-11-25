<?php

namespace App\Providers;

use App\Repositary\Blog\BlogRepo;
use App\Repositary\Blog\BlogRepoImpl;
use App\Repositary\CustomRepo\CustomRepo;
use App\Repositary\CustomRepo\CustomRepoImpl;
use App\Repositary\DineshCustom\DineshCustomRepo;
use App\Repositary\DineshCustom\DineshCustomRepoImpl;
use App\Repositary\Stripe\StripeRepo;
use App\Repositary\Stripe\StripeRepoImpl;
use App\Repositary\StripeRepo\StripeRepoRepo;
use App\Repositary\StripeRepo\StripeRepoRepoImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomRepo::class,CustomRepoImpl::class);
        $this->app->bind(BlogRepo::class,BlogRepoImpl::class);
        $this->app->bind(StripeRepo::class,StripeRepoImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
