<?php

namespace TeenQuotes\Tags;

use Illuminate\Support\ServiceProvider;
use TeenQuotes\Tags\Repositories\CachingTagRepository;
use TeenQuotes\Tags\Repositories\DbTagRepository;
use TeenQuotes\Tags\Repositories\TagRepository;

class TagsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerBindings();
    }

    private function registerBindings()
    {
        $this->app->bind(TagRepository::class, function () {
            return new CachingTagRepository(new DbTagRepository());
        });
    }
}
