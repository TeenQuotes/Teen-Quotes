<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeenQuotes\Countries;

use Illuminate\Support\ServiceProvider;
use TeenQuotes\Countries\Localisation\CityDetector;
use TeenQuotes\Countries\Localisation\CountryDetector;
use TeenQuotes\Countries\Localisation\GeoIPCityDetector;
use TeenQuotes\Countries\Localisation\GeoIPCountryDetector;
use TeenQuotes\Countries\Models\Country;
use TeenQuotes\Countries\Repositories\CachingCountryRepository;
use TeenQuotes\Countries\Repositories\CountryRepository;
use TeenQuotes\Countries\Repositories\DbCountryRepository;
use TeenQuotes\Tools\Namespaces\NamespaceTrait;

class CountriesServiceProvider extends ServiceProvider
{
    use NamespaceTrait;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerCommands();
    }

    private function registerBindings()
    {
        $this->app->bind(CountryRepository::class, function () {
            $eloquentRepo = new DbCountryRepository();

            return new CachingCountryRepository($eloquentRepo);
        });

        $geoIP = $this->app['geoip'];

        $this->app->bind(CityDetector::class, function () use ($geoIP) {
            return new GeoIPCityDetector($geoIP);
        });

        $this->app->bind(CountryDetector::class, function () use ($geoIP) {
            $countriesRepo = $this->app->make(CountryRepository::class);

            $array = $countriesRepo->listNameAndId();
            $default = Country::getDefaultCountry();

            $instance = new GeoIPCountryDetector(array_keys($array), array_values($array), $geoIP);
            $instance->setDefault($default);

            return $instance;
        });
    }

    private function registerCommands()
    {
        $commandName = $this->getBaseNamespace().'Console\MostCommonCountryCommand';

        $this->app->bindShared('countries.console.mostCommonCountry', function ($app) use ($commandName) {
            return $app->make($commandName);
        });

        $this->commands('countries.console.mostCommonCountry');
    }
}
