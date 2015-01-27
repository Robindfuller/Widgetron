<?php namespace Fuller\Widgetron;

use DOMNode;

class HtmlWidgetReference {


    protected $referenceNode;


    public function __construct(DOMNode $referenceNode)
    {
        $this->referenceNode = $referenceNode;
    }

    /**
     * Get the original widget reference DOMNode
     *
     * @return DOMNode
     */
    public function getNode()
    {
        return $this->referenceNode;
    }

    /**
     * Get the widget name from a reference node attribute
     */
    public function getWidgetName()
    {
        return $this->referenceNode->getAttribute('data-widget');
    }

    /**
     * Get widget configuration from the reference node attributes
     */
    public function getWidgetConfig()
    {
        $config = [];

        foreach($this->referenceNode->attributes as $attrName => $attrNode)
        {
            $key = preg_replace('/data-widget(\-?)/', '', $attrName);

            $value = $attrNode->nodeValue;

            if(preg_match('/^\[(.+)\]$/i', $value, $matches))
            {
                $value = explode('\',\'', trim($matches[1], '\''));
            }

            if($key) $config[$key] = $value;
        }

        return $config;
    }

    /**
     * Get the content inside the reference node
     */
    public function getWidgetContent()
    {
        return $this->referenceNode->nodeValue;
    }

} 