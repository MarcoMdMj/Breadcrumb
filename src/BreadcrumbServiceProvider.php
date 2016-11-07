<?php

namespace MarcoMdMj\Breadcrumb;

use Illuminate\Support\ServiceProvider;

/**
 * Breadcrumb Service Provider
 *
 * @package MarcoMdMj\Breadcrumb
 */
class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Register the breadcrumbs.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../rules/breadcrumbs.php' => base_path('routes/breadcrumbs.php')
        ], 'breadcrumbs');

        $this->registerBreadcrumbs();
    }

    /**
     * Register the service provider and facade.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Breadcrumb::class);

        $this->registerFacade();
    }

    /**
     * If the breadcrumbs.php file exists, open it to register the sections
     * defined in there.
     *
     * @return void
     */
    public function registerBreadcrumbs()
    {
        if (file_exists($file = $this->app['path.base'] . '/routes/breadcrumbs.php'))
        {
            require $file;
        }
    }

    /**
     * Register the breadcrumb facade.
     *
     * @return void
     */
    private function registerFacade()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('Breadcrumb', Facade\Breadcrumb::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Breadcrumb::class];
    }
}
