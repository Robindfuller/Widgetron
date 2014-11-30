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
        $this->package('fuller/widgetron');

        $this->app->booted(function () {

            $this->app->bind('widgetron.html', function ($app) {

                $config = $app['config']->get('widgetron::available');

                $widgetron = new HtmlWidgetReferenceProcessor();
                $widgetron->registerWidget($config);

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
