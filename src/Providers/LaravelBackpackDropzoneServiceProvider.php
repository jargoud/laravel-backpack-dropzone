<?php

namespace Jargoud\LaravelBackpackDropzone\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelBackpackDropzoneServiceProvider extends ServiceProvider
{
    public const NAMESPACE = 'dropzone';

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAMESPACE);

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../resources/views' => resource_path('views/vendor/' . self::NAMESPACE),
                ],
                'views'
            );

            $this->publishes(
                [
                    __DIR__ . '/../../resources' => public_path('packages/' . self::NAMESPACE),
                ],
                'assets'
            );
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->setupRoutes();
        $this->registerConfig();
    }

    protected function setupRoutes(): self
    {
        // by default, use the routes file provided in vendor
        $routeFilePath = '/routes/backpack/' . self::NAMESPACE . '.php';
        $routeFilePathInUse = __DIR__ . '/../../' . $routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        $customRouteFilePath = base_path() . $routeFilePath;
        if (file_exists($customRouteFilePath)) {
            $routeFilePathInUse = $customRouteFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);

        return $this;
    }

    protected function registerConfig(): self
    {
        $configFileName = self::NAMESPACE . '.php';
        $configPath = __DIR__.'/../../config/' . $configFileName;

        // Publish the config
        $this->publishes([
            $configPath => config_path($configFileName),
        ]);

        // Merge the default config to prevent any crash or unfilled configs
        $this->mergeConfigFrom(
            $configPath,
            self::NAMESPACE
        );

        return $this;
    }
}
