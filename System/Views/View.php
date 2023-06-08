<?php

namespace App\Views;

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


    public function count()
    {
        return (int)$this::count($this->data);
    }

    /**
     * @param $file
     * @return string
     * Заменяет ссылки
     */
    public function replaceLink($file){

        preg_match_all("/\.\.\//", $file, $results, PREG_PATTERN_ORDER);

        $links = array_unique($results[0]);

        $newLinks = ['App/'];

        $file = str_replace($links, $newLinks, $file);

        return $file;

    }

    /**
     * @param $temlate
     * @return string
     * Готовит шаблон к отображеню
     */
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

    /**
     * @param $temlate
     * подключает шаблон, выводит на экран
     */
    public function display($temlate)
    {
        echo $this->render($temlate);
    }

    /**
     * @param array $obj
     * Отображает JSON
     */
    public function JSON($obj){
        echo json_encode($obj);
    }

}