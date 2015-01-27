<?php namespace Fuller\Widgetron;

use DOMDocument;
use DomXpath;

class HtmlWidgetReferenceScanner {


    protected $context;


    public function __construct(DOMDocument $context)
    {
        $this->context = $context;
    }


    public function getWidgetReferences()
    {
        $widgetReferences = [];

        foreach($this->getQuery() as $node)
        {
            $widgetReferences[] = new HtmlWidgetReference($node);
        }

        return $widgetReferences;
    }


    protected function getQuery()
    {
        $xpath = new DomXpath($this->context);

        return $xpath->query('//*[@data-widget]');
    }

} 