<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeenQuotes\Mail;

use Illuminate\Support\ServiceProvider;
use Mandrill as BaseMandrill;

class MandrillServiceProvider extends ServiceProvider
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
        $this->app->bind('mandrill', function () {
            $mandrillSecret = $this->app['config']->get('services.mandrill.secret');
            $mandrillAPI = new BaseMandrill($mandrillSecret);

            return new Mandrill($mandrillAPI, $this->app->make('TeenQuotes\Users\Repositories\UserRepository'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mandrill'];
    }
}
