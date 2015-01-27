<?php
/**
 * Created by PhpStorm.
 * User: robinfuller
 * Date: 25/11/14
 * Time: 11:08
 */

namespace Fuller\Widgetron;


abstract class Widget {

    protected $intendedName;

    protected $config = [];

    protected $content;


    public function __construct($config = null, $content = null, $intendedName)
    {
        $this->setConfig($config);

        $this->content = $content;

        $this->setIntendedName($intendedName);
    }

    protected function setConfig($config)
    {
        if(is_array($config))
        {
            $this->config = array_merge($this->config, $config);
        }
    }


    protected function setIntendedName($name)
    {
        $this->intendedName = $name;
    }


    abstract public function render();

} 