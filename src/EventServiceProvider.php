<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/9 0009
 * Time: 14:31
 */

namespace CherryneChou\LaravelUpload;

use Illuminate\Support\ServiceProvider;

/**
 * Class EventServiceProvider
 * @package CherryneChou\LaravelUpload
 */
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'CherryneChou\LaravelUpload\Events\Uploaded' => [
            'CherryneChou\LaravelUpload\Listeners\ImageUploadedStore'
        ]
    ];

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        $events = app('events');

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }
}