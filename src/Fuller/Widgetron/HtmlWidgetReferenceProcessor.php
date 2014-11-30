<?php namespace Fuller\Widgetron;

use ReflectionClass;
use DOMDocument;
use ErrorException;
use Masterminds\HTML5;

class HtmlWidgetReferenceProcessor implements WidgetReferenceProcessor
{


    /**
     * @var DOMDocument
     */
    protected $context;


    protected $rawContext;


    protected $widgetsAvailable;


    protected $html5;


    public function __construct()
    {
        $this->html5 = new HTML5();

        $this->widgetsAvailable = [];
    }

    /**
     * Register a widget class with a name
     *
     * @param $name
     * @param $className
     */
    public function registerWidget($name, $className = null)
    {
        if(is_array($name))
        {
            foreach($name as $widgetName=>$className)
            {
                $this->registerWidget($widgetName, $className);
            }
            return;
        }

        $this->widgetsAvailable[$name] = $className;
    }


    public function process($context)
    {
        $this->setContext($context);
        return $this->execute();
    }


    protected function setContext($context)
    {
        $this->rawContext = $context;
        $this->context = $this->html5->loadHTML($context);
    }


    protected function execute()
    {
        $scanner = new HtmlWidgetReferenceScanner($this->context);
        $widgetReferences = $scanner->getWidgetReferences();

        foreach($widgetReferences as $widgetReference)
        {
            $widgetHtml = $this->renderWidget($widgetReference);

            $this->replaceWidgetReference($widgetReference, $widgetHtml);
        }

        return $this->getHtml();
    }


    protected function getWidgetClassFromName($widgetName)
    {
        if(!array_key_exists($widgetName, $this->widgetsAvailable))
        {
            return null;
        }

        return $this->widgetsAvailable[$widgetName];
    }


    protected function renderWidget(HtmlWidgetReference $widgetReference)
    {
        $widgetName = $widgetReference->getWidgetName();

        $widgetClassName = $this->getWidgetClassFromName($widgetName);

        if(is_null($widgetClassName)) return $this->getErrorHtml('Widget ' . $widgetName . ' does not have a registered class.');

        $r = new ReflectionClass($widgetClassName);

        return $r->newInstance(
            $widgetReference->getWidgetConfig(),
            $widgetReference->getWidgetContent()
        )->render();
    }


    protected function replaceWidgetReference(HtmlWidgetReference $widgetReference, $widgetHtml)
    {
        $fragment = $this->context->createDocumentFragment();

        try
        {
            $fragment->appendXML($widgetHtml);
        }
        catch (ErrorException $exception)
        {
            $errorHtml = $this->getErrorHtml($exception->getMessage());
            $fragment->appendXML($errorHtml);
        }

        $widgetReference->getNode()->parentNode->replaceChild($fragment, $widgetReference->getNode());
    }


    /**
     * @return string
     */
    protected function getHtml()
    {
        $this->context->formatOutput = true;
        $html = $this->html5->saveHTML($this->context);

        if($this->contextIsFragment())
        {
            return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $html);
        }

        return $html;
    }

    /**
     * @return bool
     */
    protected function contextIsFragment()
    {
        return preg_match('/<\/html>$/i', $this->rawContext) === false;
    }

    /**
     * @param $message
     * @return string
     */
    protected function getErrorHtml($message)
    {
        return '<div class="widget widget-error">'.$message.'</div>';
    }

} 