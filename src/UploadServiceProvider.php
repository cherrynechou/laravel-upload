<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/6 0006
 * Time: 10:25
 */

namespace CherryneChou\LaravelUpload;

use CherryneChou\LaravelUpload\Traits\ComparesVersionsTrait;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

/**
 * Class UploadImageServiceProvider
 * @package Dcat\Admin\Extension\Form\Upload
 */
class UploadServiceProvider extends ServiceProvider
{
    use ComparesVersionsTrait;

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-upload');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if (file_exists($routes = __DIR__.'/../routes/web.php')) {
            $this->loadRoutesFrom($routes);
        }

        //判断 版本号
        if ($this->versionCompare($this->app->version(), "8.*", ">")) {
            Paginator::useBootstrap();
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([ __DIR__.'/../config' => config_path() ], 'upload.php' );
        }
    }

    /**
     * event
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('CherryneChou\LaravelUpload\EventServiceProvider');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}