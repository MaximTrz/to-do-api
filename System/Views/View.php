<?php

namespace System\Views;

use App\Config;

/**
 * Class View
 * @package App\Views
 * Представление
 */
class View
implements \Countable
{
    protected $data = [];

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[name]);
    }


    public function count(): int
    {
        return (int)$this::count($this->data);
    }


    public function render($temlate)
    {

        $config = Config::getInstace();
        $temlatePath = $config->data['templates']['path'].'/';

        ob_start();

        foreach ($this->data as $key => $value)
        {
            $$key = $value;
        }

        include ($temlatePath.$temlate);

        $content = ob_get_contents();

        ob_clean();

        return $content;

    }


    public function display($temlate)
    {
        echo $this->render($temlate);
    }

    public function JSON($obj){
        return json_encode($obj);
    }

}