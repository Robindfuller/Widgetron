<?php namespace Fuller\Widgetron;

use Illuminate\Support\ServiceProvider;

class WidgetronServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
    public function register()
    {

        $configFile = __DIR__ . '/../../config/config.php';

        $this->mergeConfigFrom($configFile, 'widgetron');

        $this->publishes([
            $configFile => config_path('widgetron.php')
        ]);


        $this->app->booted(function () {

            $this->app->bind('widgetron.html', function ($app) {

                $available = $app['config']->get('widgetron::available');
                $default = $app['config']->get('widgetron::default');

                $widgetron = new HtmlWidgetReferenceProcessor();
                $widgetron->registerWidget($available);
                $widgetron->setDefaultWidget($default);

                return $widgetron;
            });

        });
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
