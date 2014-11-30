<?php namespace Fuller\Widgetron;

/**
 * A super simple content widget processing system.
 *
 * Interface WidgetReferenceProcessor
 * @package Fuller\Widgetron\Contracts
 */
interface WidgetReferenceProcessor
{
    /**
     * Register a widget
     *
     * @param string $name
     * @param string $className
     */
    public function registerWidget($name, $className = null);

    /**
     * Process the widget references in the given context
     *
     * @param mixed $context
     * @return mixed
     */
    public function process($context);
}